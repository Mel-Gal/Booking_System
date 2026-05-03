FROM php:8.2-cli

# Install tools needed for PostgreSQL and Laravel
RUN apt-get update -y && apt-get install -y libpq-dev unzip

# Install PHP extensions for Postgres
RUN docker-php-ext-install pdo pdo_pgsql

# Install Composer to manage Laravel packages
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set up the working folder
WORKDIR /app
COPY . /app

# Install Laravel dependencies
RUN composer install --optimize-autoloader --no-dev

# Start the Laravel server on the port Render gives us
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
