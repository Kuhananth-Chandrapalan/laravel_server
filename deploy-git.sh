#!/bin/bash

# Git-based Deployment Script for CloudPanel
# Run this script on the server via SSH after pushing to GitHub

set -e

echo "🚀 Starting Git-based deployment..."

# Navigate to project directory
cd /home/test/htdocs/test.serve

echo "📥 Pulling latest changes from Git..."
git pull origin main

echo "📦 Installing/updating PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "📝 Setting up environment file..."
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "✅ Created .env from .env.example"
    else
        echo "⚠️  .env.example not found. Creating basic .env file..."
        cat > .env << 'ENVFILE'
APP_NAME=Laravel
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://test.serve

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=/home/test/htdocs/test.serve/database/database.sqlite

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
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
ENVFILE
        echo "✅ Created basic .env file"
    fi
fi

echo "🔑 Generating application key (if needed)..."
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

echo "📁 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R test:test storage bootstrap/cache

echo "🗄️  Running database migrations..."
php artisan migrate --force

echo "📦 Installing/updating Node.js dependencies..."
npm install

echo "🏗️  Building frontend assets..."
npm run build

echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed successfully!"
echo ""
echo "📋 Your site is now updated with the latest changes from Git!"

