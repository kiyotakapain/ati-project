# Multi-stage build: build frontend then build PHP image with Nginx+PHP-FPM
FROM node:20 AS build
WORKDIR /app
COPY package*.json vite.config.js ./
COPY resources resources
RUN npm ci --silent
RUN npm run build

FROM php:8.4-fpm

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    nginx \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    libzip-dev \
  && docker-php-ext-install pdo pdo_mysql gd zip mbstring bcmath xml intl \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy built frontend assets
COPY --from=build /app/public/build public/build

# Copy application source
COPY . /var/www/html

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && rm composer-setup.php

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-progress
RUN composer dump-autoload -o

# Ensure storage and cache directories exist
RUN mkdir -p storage framework/cache bootstrap/cache public/processed public/uploads \
  && touch database/database.sqlite \
  && chown -R www-data:www-data /var/www/html

COPY deploy/nginx.conf /etc/nginx/nginx.conf
COPY deploy/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]
