FROM php:8.2-fpm

# Install dependencies
RUN apt-get update \
    && apt-get install -y \
        git \
        supervisor \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libonig-dev \
        libicu-dev \
        libssl-dev \
        libzip-dev \
        zlib1g-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype=/usr --with-jpeg=/usr \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring zip intl \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . /var/www/html/backend
COPY .build/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html/backend

# Fix permissions
# RUN chown -R www-data:www-data /var/www/html/backend
# RUN chmod -R 755 /var/www/html/backend

# Expose port 9000 and start supervisord
EXPOSE 9000
ENTRYPOINT [ "supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf", "-n"]
