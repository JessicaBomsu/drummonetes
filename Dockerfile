# Estágio 1: PHP base
FROM php:8.1-fpm-bullseye AS vendor_test
WORKDIR /app_test
RUN echo "<?php echo 'PHP Test Stage OK';" > index.php

# Estágio 2: Nginx base
FROM nginx:1.25-alpine AS final_test
WORKDIR /var/www/html_test
COPY --from=vendor_test /app_test/index.php .
# Copiar uma configuração Nginx super simples que apenas serve este PHP
RUN echo "server { listen ${PORT:-8080}; root /var/www/html_test; location ~ \.php$ { fastcgi_pass 127.0.0.1:9000; include fastcgi_params; fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;} }" > /etc/nginx/conf.d/default.conf
# (Nota: este Nginx não tem PHP-FPM rodando neste estágio, é só para testar o build e o Nginx servindo algo estático, ou para um PHP-FPM muito básico)
# Para um teste real com PHP-FPM, precisaríamos do entrypoint.sh
# Vamos simplificar ainda mais para o teste de parse do FROM:
# Apenas para testar o FROM do Nginx:
# FROM nginx:1.25-alpine
# RUN echo "<h1>Teste Nginx OK</h1>" > /usr/share/nginx/html/index.html
# EXPOSE 8080
# CMD ["nginx", "-g", "daemon off;"]

# Vamos usar o que tínhamos, mas garantir que a linha FROM do nginx esteja isolada
# Este é o Dockerfile.test
FROM php:8.1-fpm-bullseye AS vendor
WORKDIR /var/www/html
RUN apt-get update && apt-get install -y curl # Apenas uma dependência simples
COPY composer.json composer.lock ./
# Para simplificar o teste, vamos pular o composer install por enquanto
# RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts
COPY . .
RUN echo "Teste Laravel no estágio vendor" > public/test.txt

# Estágio 2
FROM nginx:1.25-alpine # <--- Esta seria sua "linha 62" conceitualmente
WORKDIR /var/www/html
COPY --from=vendor /var/www/html/public .
RUN echo "server { listen ${PORT:-8080}; root /var/www/html; index index.php index.html test.txt; location / { try_files \$uri \$uri/ /index.php?\$query_string; } location ~ \.php\$ { return 404; } }" > /etc/nginx/conf.d/default.conf
EXPOSE 8080
CMD ["nginx", "-g", "daemon off;"]