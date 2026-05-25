#!/bin/bash
set -e

cd /var/www/html

chown -R www-data:www-data writable
chmod -R 775 writable

exec "$@"
