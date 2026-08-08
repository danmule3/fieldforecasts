FROM php:8.4-cli-bookworm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_pgsql zip gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node (for building frontend assets)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Laravel storage/cache permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
