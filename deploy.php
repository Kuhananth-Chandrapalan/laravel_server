<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'laravel_server');

// Project repository
set('repository', 'https://github.com/Kuhananth-Chandrapalan/laravel_server.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', [
    '.env',
]);

add('shared_dirs', [
    'storage',
    'bootstrap/cache',
]);

// Writable dirs by web server
add('writable_dirs', [
    'storage',
    'bootstrap/cache',
]);

// Disable ACL and use chmod instead
set('writable_mode', 'chmod');
set('writable_use_sudo', false);

// Number of releases to keep
set('keep_releases', 5);

// Hosts
host('production')
    ->set('hostname', '192.168.1.110')
    ->set('remote_user', 'bohar')
    ->set('deploy_path', '/home/test/htdocs/test.serve')
    ->set('branch', 'main');

// Tasks

// Custom task: Check and setup deployment directory permissions (non-sudo)
// Note: This assumes the directory structure was set up manually with proper permissions
task('deploy:setup_permissions', function () {
    $deployPath = get('deploy_path');

    // Check if base deployment directory exists and is accessible
    if (!test("[ -d $deployPath ]")) {
        writeln("<error>ERROR: Deployment directory $deployPath does not exist or is not accessible.</error>");
        writeln("<error>Please run the one-time setup on the server first:</error>");
        writeln("<error>See DEPLOYMENT.md for setup instructions or run deploy-setup-server.sh</error>");
        throw new \Exception("Deployment directory not accessible. Run one-time setup first.");
    }

    // Create Deployer-specific subdirectories (will work if bohar has write access to parent)
    // Use -p to avoid errors if directories already exist
    run("mkdir -p $deployPath/.dep 2>/dev/null || true");
    run("mkdir -p $deployPath/releases 2>/dev/null || true");
    run("mkdir -p $deployPath/shared 2>/dev/null || true");

    // Try to set permissions (will only work if bohar owns the directories or has group write)
    // If this fails, the directories need to be set up manually on the server
    run("chmod -R 775 $deployPath/.dep 2>/dev/null || true");
    run("chmod -R 775 $deployPath/releases 2>/dev/null || true");
    run("chmod -R 775 $deployPath/shared 2>/dev/null || true");

    // Ensure deployment directory itself has group write for symlink creation
    run("chmod 775 $deployPath 2>/dev/null || true");

    writeln('✅ Deployment directory structure checked');
})->desc('Setup deployment directory permissions');

// Custom task: Build frontend assets
task('deploy:build', function () {
    run('cd {{release_path}} && npm install');
    run('cd {{release_path}} && npm run build');
})->desc('Build frontend assets');

// Override deploy:writable to use chmod instead of ACL
task('deploy:writable', function () {
    $dirs = get('writable_dirs');
    $releasePath = get('release_path');

    foreach ($dirs as $dir) {
        $path = "$releasePath/$dir";
        if (test("[ -d $path ]")) {
            run("chmod -R 775 $path 2>/dev/null || true");
        }
    }

    writeln('✅ Writable directories permissions set');
})->desc('Set writable directories permissions');

// Custom task: Set permissions (without sudo - requires proper group setup)
task('deploy:permissions', function () {
    // Set permissions - will work if bohar has group write access
    run('chmod -R 775 {{release_path}}/storage {{release_path}}/bootstrap/cache 2>/dev/null || true');
    // Note: chown requires sudo, so we skip it if not possible
    // The directories should already have correct ownership from the release creation
})->desc('Set file permissions');

// Custom task: Setup environment file if it doesn't exist
task('deploy:env', function () {
    $sharedPath = get('deploy_path') . '/shared';
    $envFile = $sharedPath . '/.env';
    $envExample = get('release_path') . '/.env.example';

    // Ensure shared directory exists
    run("mkdir -p $sharedPath");

    // Check if .env exists in shared directory
    if (!test("[ -f $envFile ]")) {
        if (test("[ -f $envExample ]")) {
            run("cp $envExample $envFile");
            writeln('✅ Created .env from .env.example');
        } else {
            // Create basic .env file
            $deployPath = get('deploy_path');
            run("cat > $envFile << 'ENVEOF'
APP_NAME=Laravel
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://test.serve

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=$deployPath/database/database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=\"hello@example.com\"
MAIL_FROM_NAME=\"\${APP_NAME}\"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME=\"\${APP_NAME}\"
ENVEOF
");
            writeln('✅ Created basic .env file');
        }
    }

    // Note: APP_KEY generation is moved to deploy:generate_key task
    // which runs after vendors are installed
})->desc('Setup environment file');

// Custom task: Generate application key (runs after vendors are installed)
task('deploy:generate_key', function () {
    $sharedPath = get('deploy_path') . '/shared';
    $envFile = $sharedPath . '/.env';

    // Generate APP_KEY if not set (check in shared .env)
    $appKeyCheck = run("grep '^APP_KEY=' $envFile 2>/dev/null | head -1 || echo ''");
    $appKeyValue = trim($appKeyCheck);
    if (empty($appKeyValue) || $appKeyValue === 'APP_KEY=' || strpos($appKeyValue, 'base64:') === false) {
        run('cd {{release_path}} && {{bin/php}} artisan key:generate --force');
        writeln('✅ Generated application key');
    }
})->desc('Generate application key');

// Override the deploy:vendors task to use --no-dev for production
task('deploy:vendors', function () {
    if (!commandExist('unzip')) {
        writeln('<comment>To speed up composer installation setup "unzip" command with PHP zip extension.</comment>');
    }
    run('cd {{release_path}} && {{bin/composer}} {{composer_options}}');
});

// Set composer options for production
set('composer_options', 'install --no-dev --optimize-autoloader --no-interaction --prefer-dist');

// Override the deploy:migrate task to use --force flag
task('deploy:migrate', function () {
    run('cd {{release_path}} && {{bin/php}} artisan migrate --force');
})->desc('Run database migrations');

// Ensure .dep directory has proper permissions before locking
task('deploy:ensure_dep_permissions', function () {
    $deployPath = get('deploy_path');
    $depDir = "$deployPath/.dep";

    // Check if base directory exists
    if (!test("[ -d $deployPath ]")) {
        writeln("<error>ERROR: Deployment directory $deployPath does not exist.</error>");
        writeln("<error>Please run the one-time setup on the server first.</error>");
        writeln("<error>See DEPLOYMENT.md or run deploy-setup-server.sh on the server.</error>");
        throw new \Exception("Deployment directory not accessible. Run one-time setup first.");
    }

    // Create .dep directory if it doesn't exist (suppress errors if permission denied)
    $result = run("mkdir -p $depDir 2>&1 || true");

    // Check if .dep directory was created or already exists
    if (!test("[ -d $depDir ]")) {
        writeln("<error>WARNING: Cannot create .dep directory. Please run one-time setup on server.</error>");
        writeln("<error>Run: bash deploy-setup-server.sh on the server (with sudo)</error>");
        throw new \Exception("Cannot create .dep directory. Run one-time setup first.");
    }

    // Set permissions (will work if bohar has write access)
    run("chmod -R 775 $depDir 2>/dev/null || true");

    writeln('✅ .dep directory permissions ensured');
})->desc('Ensure .dep directory permissions');

// Run permission setup before deploy:setup
before('deploy:setup', 'deploy:setup_permissions');

// Ensure .dep permissions before locking
before('deploy:lock', 'deploy:ensure_dep_permissions');

// Main deployment task
task('deploy', [
    'deploy:prepare',
    'deploy:env',           // Setup .env file (before vendors)
    'deploy:vendors',       // Install Composer dependencies
    'deploy:generate_key',  // Generate APP_KEY (after vendors, before other artisan commands)
    'deploy:permissions',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'artisan:route:cache',
    'deploy:build',
    'deploy:publish',
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'deploy:migrate');

