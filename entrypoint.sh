#!/bin/sh
set -e

# Iniciar PHP-FPM em segundo plano
# A configuração padrão do PHP-FPM (ex: www.conf) geralmente já está correta
# para escutar em 127.0.0.1:9000 ou em um socket.
# A imagem base php:8.1-fpm-bullseye já vem com PHP-FPM configurado.
php-fpm -D

# Substituir a variável PORT no arquivo de configuração do Nginx, se existir
# Isso garante que o Nginx escute na porta correta definida pelo Cloud Run
if [ ! -z "$PORT" ]; then
  sed -i "s/\${PORT:-8080}/$PORT/g" /etc/nginx/conf.d/app.conf
else
  sed -i "s/\${PORT:-8080}/8080/g" /etc/nginx/conf.d/app.conf
fi

# Iniciar Nginx em primeiro plano
nginx -g 'daemon off;'