#!/usr/bin/env bash
#
# Pokretanje sajta za lokalni razvoj.
#
#   ./start.sh            → pokreće server na http://127.0.0.1:8000
#   PORT=8080 ./start.sh  → pokreće na drugom portu
#
set -e
cd "$(dirname "$0")"

PORT="${PORT:-8000}"

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
    echo "→ Kreiran je .env iz .env.example (SQLite, bez dodatnih podešavanja)."
fi

if [ ! -d vendor ]; then
    echo "→ composer install..."
    composer install --no-interaction
fi

if [ ! -f public/build/manifest.json ]; then
    [ -d node_modules ] || { echo "→ npm install..."; npm install; }
    echo "→ npm run build..."
    npm run build
fi

FRESH_DB=0
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    FRESH_DB=1
fi

php artisan migrate --force
if [ "$FRESH_DB" = "1" ]; then
    echo "→ seed (admin, podešavanja, demo sadržaj)..."
    php artisan db:seed --force
fi
php artisan storage:link >/dev/null 2>&1 || true
php artisan config:clear >/dev/null

ADMIN_PATH=$(grep -E '^ADMIN_PATH=' .env | cut -d= -f2- | tr -d '"')
ADMIN_PATH="${ADMIN_PATH:-admin}"

echo ""
echo "════════════════════════════════════════════════"
echo "  Sajt:       http://127.0.0.1:${PORT}/sr"
echo "  Omladinci:  http://127.0.0.1:${PORT}/sr/omladinci"
echo "  Vesti:      http://127.0.0.1:${PORT}/vesti"
echo "  Admin:      http://127.0.0.1:${PORT}/${ADMIN_PATH}"
echo "════════════════════════════════════════════════"
echo ""

exec php artisan serve --host=127.0.0.1 --port="$PORT"
