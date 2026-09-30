#!/bin/bash
set -e

echo "🚀 Deploying partner-management..."

cd /var/www/html/partner-management

echo "📥 Fetch latest source..."
git fetch origin
git reset --hard origin/master

echo "🐳 Build & restart containers..."
docker compose -f docker-compose.server.yml up -d --build --force-recreate

echo "⏳ Waiting for PHP container..."
until docker exec partner_php php -v >/dev/null 2>&1; do
    sleep 2
done

echo "🗄️ Running migrations..."
docker exec partner_php php artisan migrate --force

echo "⚙️ Clearing Laravel cache..."
docker exec partner_php php artisan optimize:clear

echo "⚙️ Rebuilding Laravel cache..."
docker exec partner_php php artisan config:cache
docker exec partner_php php artisan route:cache
docker exec partner_php php artisan view:cache

echo "🔗 Creating storage link..."
docker exec partner_php php artisan storage:link || true

echo "🔐 Fix storage permissions..."
docker exec -u root partner_php find /var/www/html/storage -type d -exec chmod 777 {} \;
docker exec -u root partner_php find /var/www/html/storage -type f -exec chmod 666 {} \;
docker exec -u root partner_php chown -R 33:33 /var/www/html/storage

echo "📦 Container status..."
docker compose -f docker-compose.server.yml ps

echo "✅ Deployment selesai!"
