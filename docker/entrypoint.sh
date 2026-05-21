#!/bin/bash
set -e

cd /var/www/html

# Garante .env (Coolify pode injetar variáveis; .env.example é fallback no build)
if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
    echo "[entrypoint] .env criado a partir de .env.example"
fi

chown -R www-data:www-data writable
chmod -R 775 writable

exec "$@"
