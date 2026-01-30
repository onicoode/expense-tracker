# Base Image with PHP-FPM
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    curl \
    nginx \
    supervisor \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql mbstring zip \
    && apt-get clean

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set Working Directory
WORKDIR /var/www

# Copy composer file first (for build cache)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# copy laravel application
COPY . .

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Step 9: Configure Nginx
COPY docker/nginx/default.conf /etc/nginx/conf.d/

# Step 10: Configure Supervisor to run PHP-FPM + Nginx
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose PHP-FPM port (FastCGI)
EXPOSE 80

# Default CMD - run PHP-FPM
COPY docker-entrypoint.sh /usr/local/bin/
ENTRYPOINT [ "docker-entrypoint.sh" ]
CMD ["supervisord", "-n"]