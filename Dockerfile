# Use an official PHP image with Apache
FROM php:8.2-apache

# Install required system dependencies for PHP and Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-install intl gd zip mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (required for CI4 routing)
RUN a2enmod rewrite

# Change Apache DocumentRoot to point to the /public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# --- INI BAGIAN YANG DITAMBAHKAN UNTUK CLOUD RUN ---
# Configure Apache to listen on the port specified by Cloud Run's $PORT environment variable
ENV PORT=8080
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
# ---------------------------------------------------

# Copy all source code into the container
COPY . /var/www/html/

# Install Composer and run it
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Create the qrcode folder and set 777 permissions
RUN mkdir -p /var/www/html/writable/qrcode \
    && chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 777 /var/www/html/writable

# (Opsional) Hapus tulisan EXPOSE 80 karena Cloud Run mengabaikannya