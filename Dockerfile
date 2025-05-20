# Estágio 1: Dependências do PHP e Composer
FROM php:8.1-fpm-bullseye AS vendor

WORKDIR /var/www/html

# Instalar dependências do sistema e extensões PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl tokenizer zip fileinfo

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts
COPY . .

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
# RUN php artisan event:cache

RUN chmod -R o+w /var/www/html/storage /var/www/html/bootstrap/cache

# Estágio 2: Imagem final de produção com Nginx e PHP-FPM
FROM nginx:1.25-alpine

RUN rm /etc/nginx/conf.d/default.conf
COPY nginx-app.conf /etc/nginx/conf.d/app.conf

WORKDIR /var/www/html
COPY --chown=nginx:nginx --from=vendor /var/www/html .

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]