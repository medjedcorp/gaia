FROM php:8.0-fpm
ENV TZ Asia/Tokyo
# COPY php.ini
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# Composer install
COPY --from=composer /usr/bin/composer /usr/bin/composer

# install Node.js
COPY --from=node /usr/local/bin /usr/local/bin
COPY --from=node /usr/local/lib /usr/local/lib

RUN apt-get update && \
    apt-get -y install \
    git \
    zip \
    unzip \
    vim \
    && docker-php-ext-install pdo_mysql bcmath

WORKDIR /var/www/html
# RUN chown -R www-data:www-data /var/www/html
# RUN chown -R www-data:www-data  /var/www/html/storage/*
# RUN chmod 777 /var/www/html/storage

RUN mkdir -p /var/www/log
RUN apt-get install -y cron
RUN chmod 0644 /etc/cron.d/*
RUN touch /var/www/log/cron.log

# php fpmとcronの起動
CMD cron && /usr/local/sbin/php-fpm
