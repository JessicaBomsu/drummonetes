# Estágio 1: Dependências do PHP e Composer
FROM php:8.1-fpm-bullseye as vendor

# Argumentos para usuário e grupo (para não rodar como root)
ARG PUID=1000
ARG PGID=1000

WORKDIR /var/www/html

# Instalar dependências do sistema e extensões PHP
# Atualize esta lista conforme as necessidades do seu projeto (ex: gd, bcmath, etc.)
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
    # Para MySQL
    && docker-php-ext-install pdo pdo_mysql \
    # Outras extensões comuns do Laravel
    && docker-php-ext-install mbstring exif pcntl tokenizer zip fileinfo

# Limpar cache do apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar composer.json e composer.lock
COPY composer.json composer.lock ./

# Instalar dependências do Composer (sem dev, otimizado para produção)
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts

# Copiar o restante do código da aplicação
COPY . .

# Gerar otimizações do Laravel (opcional, mas recomendado para produção)
# Se algum desses comandos falhar no build, você pode comentá-los inicialmente
# e tentar rodá-los depois que a aplicação estiver no ar e conectada ao banco.
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
# RUN php artisan event:cache # Descomente se você usa eventos e quer cacheá-los

# Mudar o proprietário dos arquivos para o usuário não-root (www-data é comum para PHP-FPM/Nginx)
# RUN chown -R www-data:www-data /var/www/html
# Ajustar permissões para storage e bootstrap/cache para que o servidor web possa escrever
# RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
# (As permissões exatas podem depender da sua imagem base e configuração)
# Uma forma mais simples que geralmente funciona para Cloud Run onde o usuário pode ser root dentro do container inicialmente:
RUN chmod -R o+w /var/www/html/storage /var/www/html/bootstrap/cache

# Estágio 2: Imagem final de produção com Nginx e PHP-FPM
FROM nginx:1.25-alpine # Usando uma versão estável e leve do Nginx

# Argumentos para usuário e grupo (para não rodar como root)
ARG PUID=1000
ARG PGID=1000

# Remover configuração padrão do Nginx
RUN rm /etc/nginx/conf.d/default.conf

# Copiar sua configuração customizada do Nginx (o arquivo nginx-app.conf que você vai criar)
COPY nginx-app.conf /etc/nginx/conf.d/app.conf

# Copiar os arquivos da aplicação (do estágio 'vendor' onde o composer install foi feito)
WORKDIR /var/www/html
COPY --chown=nginx:nginx --from=vendor /var/www/html .

# Copiar o script de entrypoint (você precisará criar este arquivo também)
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expor a porta que o Nginx vai escutar (Cloud Run injetará a variável $PORT)
# O Nginx no nosso app.conf vai escutar na porta definida pela variável $PORT (ou 8080)
EXPOSE 8080

# Entrypoint para iniciar PHP-FPM e Nginx
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]