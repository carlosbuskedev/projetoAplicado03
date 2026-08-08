#!/bin/bash
set -e

cd /var/www/html

chown -R www-data:www-data writable
chmod -R 775 writable

echo "Rodando as migrations do CodeIgniter..."
php spark migrate

echo "Rodando os seeders iniciais..."
php spark db:seed UserSeeder

echo "Iniciando o servidor Apache..."
# O comando abaixo deve ser sempre a última linha deste arquivo!
exec "$@"
