# Estágio 1: Build de dependências
FROM php:8.1-fpm-bullseye AS vendor

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl tokenizer zip fileinfo \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts

COPY . .

RUN chmod -R o+w /var/www/html/storage /var/www/html/bootstrap/cache

# Estágio 2: Produção
FROM nginx:1.25-alpine

RUN rm /etc/nginx/conf.d/default.conf
COPY nginx-app.conf /etc/nginx/conf.d/app.conf

WORKDIR /var/www/html
COPY --from=vendor /var/www/html .

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
