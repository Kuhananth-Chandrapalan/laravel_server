#!/bin/bash

# File Upload Script for Laravel Deployment
# Run this from your LOCAL machine to upload files to the server

set -e

SERVER_USER="bohar"
SERVER_HOST="192.168.1.110"
TEMP_PATH="/tmp/laravel_deploy_$(date +%s)"
FINAL_PATH="/home/test/htdocs/test.serve"
LOCAL_PATH="."

echo "📤 Uploading Laravel files to server..."
echo "Server: ${SERVER_USER}@${SERVER_HOST}"
echo "Temporary location: ${TEMP_PATH}"
echo "Final location: ${FINAL_PATH}"
echo ""
echo "⚠️  You will be prompted for your SSH password"
echo ""

# Accept host key automatically (first time only)
ssh-keyscan -H ${SERVER_HOST} >> ~/.ssh/known_hosts 2>/dev/null || true

# Upload to temporary location (where bohar has write access)
echo "📦 Uploading files to temporary location..."
# Note: .env is excluded but .env.example should be included
rsync -avz --progress -e ssh \
    --include='.env.example' \
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
    "${LOCAL_PATH}/" "${SERVER_USER}@${SERVER_HOST}:${TEMP_PATH}/"

echo ""
echo "✅ Files uploaded to temporary location: ${TEMP_PATH}"
echo ""
echo "📋 IMPORTANT: Run these commands in PuTTY (SSH) to move files:"
echo ""
echo "   sudo rm -rf ${FINAL_PATH}/*"
echo "   sudo cp -r ${TEMP_PATH}/* ${FINAL_PATH}/"
echo "   sudo chown -R test:test ${FINAL_PATH}"
echo "   sudo chmod -R 755 ${FINAL_PATH}"
echo "   rm -rf ${TEMP_PATH}"
echo ""
echo "   Then run deployment:"
echo "   cd ${FINAL_PATH}"
echo "   bash deploy.sh"
echo ""

