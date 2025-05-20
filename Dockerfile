FROM php:8.2-apache

# Instala extensões PHP comuns do Laravel
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia os arquivos do Laravel
COPY . /var/www/html

# Ajustes de permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Define o diretório padrão
WORKDIR /var/www/html

# Instala as dependências
RUN composer install --no-dev --optimize-autoloader

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# Define a porta do Cloud Run
EXPOSE 8080

# Define o comando de entrada (entrypoint)
CMD ["apache2-foreground"]
