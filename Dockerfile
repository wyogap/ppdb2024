# Use an official PHP image with Apache
FROM richarvey/nginx-php-fpm:latest

# Change nginx config file
COPY docker/conf/nginx-site.conf /etc/nginx/sites-available/default.conf
COPY docker/conf/nginx-site-ssl.conf /etc/nginx/sites-available/default-ssl.conf

#RUN ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf
#RUN ln -s /etc/nginx/sites-available/default-ssl.conf /etc/nginx/sites-enabled/default-ssl.conf

# Copy all source code into the container
COPY . /var/www/html/

RUN rm /var/www/html/app/Config/Database.php
RUN cp /var/www/html/app/Config/Database-cloudrun.php /var/www/html/app/Config/Database.php

RUN sed -i \
        -e "s/;catch_workers_output\s*=\s*yes/catch_workers_output = yes/g" \
        -e "s/pm.max_children = 5/pm.max_children = 150/g" \
        -e "s/pm.start_servers = 2/pm.start_servers = 35/g" \
        -e "s/pm.min_spare_servers = 1/pm.min_spare_servers = 20/g" \
        -e "s/pm.max_spare_servers = 3/pm.max_spare_servers = 50/g" \
        -e "s/;pm.max_requests = 500/pm.max_requests = 500/g" \
        -e "s/user = www-data/user = nginx/g" \
        -e "s/group = www-data/group = nginx/g" \
        -e "s/;listen.mode = 0660/listen.mode = 0666/g" \
        -e "s/;listen.owner = www-data/listen.owner = nginx/g" \
        -e "s/;listen.group = www-data/listen.group = nginx/g" \
        -e "s/listen = 127.0.0.1:9000/listen = \/var\/run\/php-fpm.sock/g" \
        -e "s/^;clear_env = no$/clear_env = no/" \
        /usr/local/etc/php-fpm.d/www.conf

ENV SKIP_COMPOSER=1
ENV ENABLE_XDEBUG=0

#RUN mkdir -p /etc/letsencrypt/live/spmb2026 
#COPY docker/ssl/fullchain.pem /etc/letsencrypt/live/spmb2026/fullchain.pem
#COPY docker/ssl/privkey.pem /etc/letsencrypt/live/spmb2026/privkey.pem

# Create the qrcode folder and set 777 permissions
RUN mkdir -p /var/www/html/writable/qrcode \
    && chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 777 /var/www/html/writable

# Create the qrcode folder and set 777 permissions
RUN mkdir -p /var/www/html/app/cache/smarty_templates_cache \
    && chown -R www-data:www-data /var/www/html/app/cache/smarty_templates_cache \
    && chmod -R 777 /var/www/html/app/cache/smarty_templates_cache

# (Opsional) Hapus tulisan EXPOSE 80 karena Cloud Run mengabaikannya