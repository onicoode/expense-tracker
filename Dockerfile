# Base Image with PHP-FPM
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    bash \
    git \
    unzip \
    zip \
    oniguruma-dev \
    libpng-dev \
    libzip-dev \
    icu-dev \
    postgresql-dev \
    curl \
    nginx \
    supervisor \
    libpq-dev \
    nodejs \
    npm

# Configure and install GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# ===============================
# PHP extensions
# ===============================
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    intl \
    zip \
    gd

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

# Build Vite assets
RUN npm install && npm run build

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Step 9: Configure Nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Step 10: Configure Supervisor to run PHP-FPM + Nginx
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose PHP-FPM port (FastCGI)
EXPOSE 80

# Default CMD - run PHP-FPM
COPY docker-entrypoint.sh /usr/local/bin/
ENTRYPOINT [ "docker-entrypoint.sh" ]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
