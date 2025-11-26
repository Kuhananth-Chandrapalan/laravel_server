#!/bin/bash

# Quick script to upload just deploy.sh and .env.example

SERVER_USER="bohar"
SERVER_HOST="192.168.1.110"
SERVER_PATH="/home/test/htdocs/test.serve"

echo "📤 Uploading deploy.sh and .env.example..."

scp deploy.sh "${SERVER_USER}@${SERVER_HOST}:/tmp/deploy.sh"
scp .env.example "${SERVER_USER}@${SERVER_HOST}:/tmp/.env.example"

echo ""
echo "✅ Files uploaded to /tmp/"
echo ""
echo "📋 In PuTTY, run:"
echo "   sudo cp /tmp/deploy.sh /home/test/htdocs/test.serve/"
echo "   sudo cp /tmp/.env.example /home/test/htdocs/test.serve/"
echo "   sudo chown test:test /home/test/htdocs/test.serve/deploy.sh"
echo "   sudo chown test:test /home/test/htdocs/test.serve/.env.example"
echo "   cd /home/test/htdocs/test.serve && bash deploy.sh"

