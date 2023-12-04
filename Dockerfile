FROM php:8.2.13-apache

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .
COPY .env.example .env

RUN cp .env.example .env

# Install project dependencies
RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=8000

EXPOSE 8000
