# Git-Based Deployment Setup

## Initial Setup (Run once in PuTTY)

### Step 1: Initialize Git on Server

```bash
cd /home/test/htdocs/test.serve

# Initialize git repository
git init

# Add your GitHub repository as remote
git remote add origin https://github.com/Kuhananth-Chandrapalan/laravel_server.git

# Set default branch to main
git branch -M main

# Pull from GitHub (this will merge with existing files)
git pull origin main --allow-unrelated-histories

# Set proper ownership
sudo chown -R test:test /home/test/htdocs/test.serve
```

### Step 2: Upload the Git Deployment Script

The `deploy-git.sh` script needs to be on the server. You can either:

**Option A: Pull it from Git (after pushing)**
```bash
git pull origin main
chmod +x deploy-git.sh
```

**Option B: Create it manually on server** (if you haven't pushed it yet)

## Daily Workflow

### On Your Local Machine:
1. Make changes to your code
2. Commit changes:
   ```bash
   git add .
   git commit -m "Your commit message"
   git push origin main
   ```

### On Server (PuTTY):
```bash
cd /home/test/htdocs/test.serve
bash deploy-git.sh
```

That's it! The script will:
- Pull latest changes from GitHub
- Install/update dependencies
- Build assets
- Run migrations
- Optimize the application

## Alternative: Simple Git Pull (if no dependencies changed)

If you only changed code (no new packages), you can just:
```bash
cd /home/test/htdocs/test.serve
git pull origin main
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

