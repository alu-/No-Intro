FROM php:7.3-apache
MAINTAINER alu@byteberry.net

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    zip \
    libzip-dev \
    curl \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++ && \
    rm -rf /var/lib/apt/lists/*

# mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers and PHP extensions
RUN a2enmod rewrite headers && \
    mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    docker-php-source extract && \
    pecl install xdebug redis && \
    docker-php-ext-enable xdebug redis && \
    docker-php-source delete && \
    docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    mbstring \
    pdo_mysql \
    zip

# Composer binary from composer:latest
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Permissions
ARG uid=1000
ARG gid=1000

RUN groupmod --gid $gid www-data && \
    usermod --uid $uid --gid $gid www-data && \
    chown -R www-data:www-data /var/www
