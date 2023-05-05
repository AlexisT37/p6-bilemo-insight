FROM php:8.2-fpm-alpine

# Install PDO PostgreSQL driver
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo_pgsql
