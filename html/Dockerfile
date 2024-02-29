# Dockerfile
FROM php:8.2.7-fpm

RUN apt-get update -y && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    webp

# Install cron
RUN apt-get update -y && apt-get install -y cron

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . /app

# Add the cron job script
# COPY cronjob /etc/cron.d/cronjob
# RUN chmod 0644 /etc/cron.d/cronjob
# RUN crontab /etc/cron.d/cronjob

EXPOSE 8000
CMD bash -c "composer install && composer update && php artisan cache:clear && php artisan config:clear && php artisan route:clear && ./webp_convert.sh && php artisan serve --host=0.0.0.0 --port=8000"