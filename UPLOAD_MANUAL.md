# Manual File Upload Guide

## Using SFTP Client (FileZilla/WinSCP)

### Connection Settings:
- **Host**: `192.168.1.110`
- **Username**: `bohar`
- **Password**: (your SSH password)
- **Port**: `22`
- **Protocol**: SFTP

### Upload Steps:

1. **Connect to server** using the settings above

2. **Navigate on server** to: `/home/test/htdocs/test.serve/`

3. **Upload these files/folders** from your local project:
   - `app/`
   - `bootstrap/`
   - `config/`
   - `database/`
   - `public/`
   - `resources/`
   - `routes/`
   - `storage/` (empty the cache/sessions/views subfolders first)
   - `tests/`
   - `.env.example`
   - `artisan`
   - `composer.json`
   - `composer.lock`
   - `package.json`
   - `package-lock.json` (if exists)
   - `phpunit.xml`
   - `vite.config.js`
   - `deploy.sh`

4. **DO NOT upload**:
   - `vendor/` (will be installed on server)
   - `node_modules/` (will be installed on server)
   - `.env` (will be created on server)
   - `.git/`
   - `storage/logs/*`
   - `storage/framework/cache/*`
   - `storage/framework/sessions/*`
   - `storage/framework/views/*`

5. **After upload**, SSH into server (via PuTTY) and run:
   ```bash
   cd /home/test/htdocs/test.serve
   bash deploy.sh
   ```

## Using Command Line SFTP

```bash
# Connect via SFTP
sftp bohar@192.168.1.110

# Navigate to project directory on server
cd /home/test/htdocs/test.serve

# Upload files (from your local terminal, before connecting)
# Or use 'put' command inside sftp session
put -r app/
put -r bootstrap/
put -r config/
# ... continue for all folders

# Exit SFTP
exit
```

