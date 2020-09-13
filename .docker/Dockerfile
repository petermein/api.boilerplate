FROM alpine:3.11

ADD https://dl.bintray.com/php-alpine/key/php-alpine.rsa.pub /etc/apk/keys/php-alpine.rsa.pub

RUN apk --update add ca-certificates bash && \
    echo "https://dl.bintray.com/php-alpine/v3.11/php-7.4" >> /etc/apk/repositories

# Install packages and remove default server definition
RUN apk --no-cache add php php-fpm php-opcache php-pdo php-pdo_mysql php-redis php-json php-openssl php-curl \
    php-zlib php-xml php-phar php-intl php-dom php-xmlreader php-ctype php-session \
    php-mbstring php-gd nginx supervisor curl && \
    rm /etc/nginx/conf.d/default.conf

# Create symlink for easy php access
RUN ln -s /usr/bin/php7 /usr/bin/php

# Configure nginx
COPY ./.docker/config/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY ./.docker/config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY ./.docker/config/php.ini /etc/php7/conf.d/custom.ini

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
