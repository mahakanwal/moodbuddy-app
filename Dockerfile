# MoodBuddy - PHP 8.2 + Apache (for Render / any Docker host)
FROM php:8.2-apache

# System libs + PHP extensions used by the project
RUN apt-get update && apt-get install -y --no-install-recommends \
        libcurl4-openssl-dev libzip-dev unzip ca-certificates \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && a2enmod rewrite headers expires \
    && rm -rf /var/lib/apt/lists/*

# PHP settings (uploads, output buffering, sessions)
COPY docker/php.ini /usr/local/etc/php/conf.d/moodbuddy.ini
# Apache: listen on Render's $PORT
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start.sh

# Composer dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Application code
COPY . .
RUN rm -rf docker database .env \
    && mkdir -p admin/uploads/products admin/uploads/songs admin/uploads/memes admin/uploads/games /var/lib/php/sessions \
    && chown -R www-data:www-data admin/uploads /var/lib/php/sessions \
    && chmod +x /usr/local/bin/start.sh

ENV PORT=10000
EXPOSE 10000
CMD ["/usr/local/bin/start.sh"]
