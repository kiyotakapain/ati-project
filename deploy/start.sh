#!/usr/bin/env bash
set -e

# Substitute port placeholder in nginx config
PORT=${PORT:-10000}
sed -i "s/{{PORT}}/${PORT}/g" /etc/nginx/nginx.conf || true

# Ensure php-fpm listens on 127.0.0.1:9000 (default for php:8.2-fpm)

# Start php-fpm
php-fpm -D

# Start nginx in foreground
nginx -g 'daemon off;'
