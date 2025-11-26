#!/bin/bash

# File Upload Script using tar + scp
# Run this from your LOCAL machine to upload files to the server

set -e

SERVER_USER="bohar"
SERVER_HOST="192.168.1.110"
SERVER_PATH="/home/test/htdocs/test.serve"
LOCAL_PATH="."

echo "📦 Creating archive of Laravel files..."
echo ""

# Create a temporary archive excluding unnecessary files
TEMP_ARCHIVE="/tmp/laravel_deploy_$(date +%s).tar.gz"

tar -czf "$TEMP_ARCHIVE" \
    --exclude='vendor' \
    --exclude='node_modules' \
    --exclude='.env' \
    --exclude='.git' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='.phpunit.cache' \
    --exclude='.phpunit.result.cache' \
    --exclude='.DS_Store' \
    --exclude='Thumbs.db' \
    -C "$LOCAL_PATH" .

echo "📤 Uploading archive to server..."
echo "Server: ${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
echo "You will be prompted for your SSH password..."
echo ""

# Upload the archive
scp "$TEMP_ARCHIVE" "${SERVER_USER}@${SERVER_HOST}:/tmp/laravel_deploy.tar.gz"

echo ""
echo "📦 Extracting files on server..."
echo "You will be prompted for your SSH password again..."
echo ""

# Extract on server
ssh "${SERVER_USER}@${SERVER_HOST}" << 'ENDSSH'
cd /home/test/htdocs/test.serve
tar -xzf /tmp/laravel_deploy.tar.gz
rm /tmp/laravel_deploy.tar.gz
echo "✅ Files extracted successfully!"
ENDSSH

# Clean up local archive
rm "$TEMP_ARCHIVE"

echo ""
echo "✅ File upload completed!"
echo ""
echo "📋 Next steps:"
echo "1. SSH into your server (via PuTTY): ssh ${SERVER_USER}@${SERVER_HOST}"
echo "2. Run the deployment script: cd /home/test/htdocs/test.serve && bash deploy.sh"

