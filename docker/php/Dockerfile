FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN if [ ! -f "artisan" ]; then \
      composer create-project laravel/laravel:^11.0 . && \
      php artisan key:generate; \
    fi

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
