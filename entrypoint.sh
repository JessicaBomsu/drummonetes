#!/bin/sh
set -e

# Rodar comandos Laravel apenas se APP_KEY estiver disponível
if [ ! -z "$APP_KEY" ]; then
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
fi

# Substituir a variável PORT no Nginx (usada no Cloud Run)
if [ ! -z "$PORT" ]; then
  sed -i "s/\${PORT:-8080}/$PORT/g" /etc/nginx/conf.d/app.conf
else
  sed -i "s/\${PORT:-8080}/8080/g" /etc/nginx/conf.d/app.conf
fi

# Iniciar PHP-FPM em segundo plano
php-fpm -D

# Iniciar Nginx
nginx -g 'daemon off;'
