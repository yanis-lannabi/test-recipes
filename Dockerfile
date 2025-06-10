FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    zip \
    git \
    nodejs \
    npm

RUN docker-php-ext-install pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Fix permissions for Laravel
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www

EXPOSE 9000