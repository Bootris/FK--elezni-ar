#!/usr/bin/env bash
# Rebuild the app in place on the production server, after new code has been
# rsynced in. Invoked over SSH by .github/workflows/deploy.yml; can also be run
# by hand as root:  bash /var/www/fkzeleznicarnis.rs/bin/deploy-production.sh
set -euo pipefail

APP="$(cd "$(dirname "$0")/.." && pwd)"
cd "$APP"
export COMPOSER_ALLOW_SUPERUSER=1 NODE_OPTIONS=--max-old-space-size=1536

php artisan down --render="errors::503" --retry=15 || true
trap 'cd "$APP" && php artisan up || true' EXIT

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-progress
npm ci --no-audit --no-fund
npm run build
php artisan migrate --force --no-interaction

chown -R www-data:www-data "$APP"
chmod -R 775 "$APP/storage" "$APP/bootstrap/cache"
chmod 640 "$APP/.env"
chmod 775 "$APP/database"
chmod 664 "$APP/database/database.sqlite"

sudo -u www-data php artisan optimize
rm -rf "$APP/node_modules"          # build artifacts already in public/build
systemctl restart php8.3-fpm
systemctl restart fk-queue          # queue worker drzi stari kod u memoriji

echo "✅ $APP redeployovan."
