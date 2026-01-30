#!/bin/sh

echo "Starting container..."

# Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "Waiting for database..."

# Wait for database connection
until php -r "
try {
    new PDO(
        getenv('DB_CONNECTION') === 'pgsql'
            ? 'pgsql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT')
            : 'mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD')
    );
    echo 'DB ready';
} catch (Exception \$e) {
    exit(1);
}
"; do
  echo "Database not ready — waiting..."
  sleep 2
done

echo "Running Laravel migrations..."

php artisan migrate --force || true

echo "Caching config..."

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Starting services..."

exec "$@"
