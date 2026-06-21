# Use an official PHP image with Apache
FROM richarvey/nginx-php-fpm:latest

# Change nginx config file
COPY docker/conf/nginx-site.conf /etc/nginx/sites-available/default.conf
COPY docker/conf/nginx-site-ssl.conf /etc/nginx/sites-available/default-ssl.conf

# Copy all source code into the container
COPY . /var/www/html/

#ENV GIT_USERNAME 
#ENV GIT_PERSONAL_TOKEN 
#ENV GIT_REPO 

# Create the qrcode folder and set 777 permissions
RUN mkdir -p /var/www/html/writable/qrcode \
    && chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 777 /var/www/html/writable

# Create the qrcode folder and set 777 permissions
RUN mkdir -p /var/www/html/app/cache/smarty_templates_cache \
    && chown -R www-data:www-data /var/www/html/app/cache/smarty_templates_cache \
    && chmod -R 777 /var/www/html/app/cache/smarty_templates_cache

# (Opsional) Hapus tulisan EXPOSE 80 karena Cloud Run mengabaikannya