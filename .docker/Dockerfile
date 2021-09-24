FROM alpine:3.12 as base

# trust this project public key to trust the packages.
ADD https://packages.whatwedo.ch/php-alpine.rsa.pub /etc/apk/keys/php-alpine.rsa.pub

# make sure you can use HTTPS
RUN apk --update add ca-certificates && \
echo "@php https://packages.whatwedo.ch/php-alpine/v3.12/php-8.0" >> /etc/apk/repositories


RUN apk add --update php@php
RUN apk add --update php-fpm@php
RUN apk add --update php-common@php
RUN apk add --update php-pdo@php
RUN apk add --update php-mysqlnd@php
RUN apk add --update php-pdo_mysql@php
RUN apk add --update php-curl@php
RUN apk add --update php-session@php
RUN apk add --update php-opcache@php
RUN apk add --update php-mbstring@php
RUN apk add --update php-exif@php
RUN apk add --update php-bcmath@php
#RUN apk add --update php-json@php
RUN apk add --update php-xml@php
RUN apk add --update php-sockets@php
RUN apk add --update php-redis@php
RUN apk add --update php-dom@php
RUN apk add --update php-zlib@php
RUN apk add --update php-zip@php
RUN apk add --update php-openssl@php
RUN apk add --update php-imap@php
RUN apk add --update php-soap@php
RUN apk add --update php-gd@php
RUN apk add --update php-pcntl@php
RUN apk add --update php-phar@php
RUN apk add --update php-calendar@php

## Install packages and remove default server definition
RUN apk update && \
    apk --no-cache --update add  \
    nginx \
    supervisor \
    curl \
    bash && \
    rm /etc/nginx/conf.d/default.conf

# Create symlink for easy php access
RUN ln -s /usr/bin/php8 /usr/bin/php

# Configure nginx
COPY ./.docker/config/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY ./.docker/config/fpm-pool.conf /etc/php8/php-fpm.d/www.conf
COPY ./.docker/config/php.ini /etc/php8/conf.d/custom.ini

# Configure supervisord
COPY ./.docker/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup document root
RUN mkdir -p /var/www/html

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html && \
  chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/log/nginx

# Switch to use a non-root user from here on
USER nobody

# Add application
WORKDIR /var/www/html

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
