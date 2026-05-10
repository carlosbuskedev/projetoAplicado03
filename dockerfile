FROM php:8.2-apache

# Extensões necessárias para CodeIgniter + MySQL
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git curl \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli gd intl

# Habilita mod_rewrite (necessário para o CodeIgniter)
RUN a2enmod rewrite

# Copia os arquivos do projeto
COPY . /var/www/html/

# Define o DocumentRoot para a pasta public do CI4
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf

# Permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configuração do Apache para permitir .htaccess
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/override.conf \
    && a2enconf override

EXPOSE 80