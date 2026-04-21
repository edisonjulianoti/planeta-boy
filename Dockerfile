# Multi-stage build para Laravel 13

# Stage base - PHP 8.3 com extensões necessárias
FROM php:8.3-fpm-alpine AS base

# Instalação de dependências do sistema
RUN apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    postgresql-dev \
    mysql-client \
    nodejs \
    npm \
    autoconf \
    gcc \
    g++ \
    make \
    $PHPIZE_DEPS \
    fcgi

# Configuração de extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    intl \
    zip \
    opcache

# Instalação do Redis via PECL
RUN pecl install redis \
    && docker-php-ext-enable redis

# Instalação do Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configuração do diretório de trabalho
WORKDIR /var/www/html

# Stage builder - Instalação de dependências e build
FROM base AS builder

# Copia arquivos de dependências
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Copia arquivos do projeto
COPY . .

# Instalação de dependências PHP
RUN composer install \
    --no-interaction \
    --no-ansi \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --no-dev

# Instalação de dependências Node
RUN npm install

# Criar diretórios necessários
RUN mkdir -p /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views

# Permissões de diretórios
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    && chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Stage production - Imagem otimizada
FROM base AS production

# Copia dependências instaladas
COPY --from=builder /var/www/html/vendor /var/www/html/vendor
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# Configuração PHP de produção
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && echo "opcache.enable=1" >> "$PHP_INI_DIR/php.ini" \
    && echo "opcache.memory_consumption=128" >> "$PHP_INI_DIR/php.ini" \
    && echo "opcache.interned_strings_buffer=8" >> "$PHP_INI_DIR/php.ini" \
    && echo "opcache.max_accelerated_files=10000" >> "$PHP_INI_DIR/php.ini" \
    && echo "opcache.revalidate_freq=2" >> "$PHP_INI_DIR/php.ini" \
    && echo "opcache.fast_shutdown=1" >> "$PHP_INI_DIR/php.ini"

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1

# Exposição da porta PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
