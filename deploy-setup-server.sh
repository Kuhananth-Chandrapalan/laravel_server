#!/bin/bash

# One-time server setup script for PHP Deployer
# Run this ONCE on the server via SSH to set up directory permissions
# After this, deployments can run without sudo

set -e

DEPLOY_PATH="/home/test/htdocs/test.serve"

echo "🔧 Setting up PHP Deployer directory structure..."
echo "This script needs to be run with sudo privileges"
echo ""

# Ensure deployment directory exists
sudo mkdir -p "$DEPLOY_PATH"

# Set group ownership and permissions on deployment directory itself
# This allows bohar (if in test group) to create symlinks (release, current)
sudo chown test:test "$DEPLOY_PATH"
sudo chmod 775 "$DEPLOY_PATH"

# Create deployment directory structure
sudo mkdir -p "$DEPLOY_PATH/.dep"
sudo mkdir -p "$DEPLOY_PATH/releases"
sudo mkdir -p "$DEPLOY_PATH/shared"

# Set ownership and permissions for .dep directory (bohar needs write access)
sudo chown -R bohar:test "$DEPLOY_PATH/.dep"
sudo chmod -R 775 "$DEPLOY_PATH/.dep"

# Set ownership and permissions for releases directory
# Group ownership allows bohar (if in test group) to write
sudo chown -R test:test "$DEPLOY_PATH/releases"
sudo chmod -R 775 "$DEPLOY_PATH/releases"

# Set ownership and permissions for shared directory
sudo chown -R test:test "$DEPLOY_PATH/shared"
sudo chmod -R 775 "$DEPLOY_PATH/shared"

# Ensure bohar is in the test group (if not already)
echo ""
echo "📋 Checking if bohar is in the test group..."
if groups bohar | grep -q "\btest\b"; then
    echo "✅ bohar is already in the test group"
else
    echo "⚠️  bohar is NOT in the test group"
    echo "   Run this command to add bohar to test group:"
    echo "   sudo usermod -a -G test bohar"
    echo "   (Then bohar needs to log out and log back in for changes to take effect)"
fi

echo ""
echo "✅ Server setup complete!"
echo ""
echo "📋 Next steps:"
echo "1. If bohar was added to test group, log out and log back in"
echo "2. Run deployment from your local machine:"
echo "   vendor/bin/dep deploy production"

