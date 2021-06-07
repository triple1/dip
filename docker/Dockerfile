FROM alpine:3.13

ENV TIMEZONE Europe/Kiev

# Install packages
RUN apk --no-cache add php7 php7-fpm php7-opcache php7-mysqli php7-json php7-openssl php7-curl \
    php7-zlib php7-xml php7-phar php7-intl php7-dom php7-xmlreader php7-ctype php7-session \
    php7-mbstring php7-gd nginx supervisor curl libxml2-dev libmcrypt-dev php7-pdo_mysql php-pgsql php7-mbstring \
    icu-dev php7-intl postgresql-dev php7-pdo_pgsql php7-tokenizer php7-simplexml php7-iconv php7-xdebug php7-fileinfo php7-sockets \
    nodejs npm yarn zip php7-zip

RUN apk add --update tzdata \
    && cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apk add --update openssl && \
    rm -rf /var/cache/apk/*

# RUN mkdir -p /etc/nginx/certificates

# RUN	openssl req -nodes -x509 -newkey rsa:4096 -keyout /etc/nginx/certificates/key.pem -out /etc/nginx/certificates/cert.pem -days 365 -subj "/C=US/ST=Oregon/L=Portland/O=Company Name/OU=Org/CN=localhost"

# Configure nginx
COPY config/nginx.conf /etc/nginx/nginx.conf
# Remove default server definition
RUN rm /etc/nginx/conf.d/default.conf

# Configure PHP-FPM
COPY config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY config/php.ini /etc/php7/conf.d/custom.ini

# Configure supervisord
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup document root
RUN mkdir -p /app

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
#RUN chown -R nobody.nobody /app && \
#  chown -R nobody.nobody /run && \
#  chown -R nobody.nobody /var/lib/nginx && \
#  chown -R nobody.nobody /var/log/nginx && \
#  chown -R nobody.nobody /etc/nginx/certificates

# Switch to use a non-root user from here on
# USER nobody

# Add application
WORKDIR /app/public
# COPY --chown=nobody ./engage-intake/ /app/

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
# HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
