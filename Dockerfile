FROM php:8.2-fpm

# Set the working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libicu-dev \
    libpq-dev \
    libmemcached-dev \
    libssl-dev \
    libmcrypt-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    cron \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application source code
COPY ./src /var/www/
# Copy configuration files
#COPY ./config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
#COPY ./config/php.ini /usr/local/etc/php/conf.d/

# Copy existing application directory permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Generate application key
RUN php artisan key:generate --no-interaction

# Cache configuration
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose port 9000 and start supervisord
EXPOSE 9000 