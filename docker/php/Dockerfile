FROM php:8.0-fpm
ENV TZ Asia/Tokyo
# COPY php.ini
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# Composer install
COPY --from=composer /usr/bin/composer /usr/bin/composer

# RUN apt-get update && apt-get -y install locales && localedef -f UTF-8 -i ja_JP ja_JP.UTF-8
# ENV LANG ja_JP.UTF-8
# ENV LANGUAGE ja_JP:ja
# ENV LC_ALL ja_JP.UTF-8
# ENV TZ JST-9
# ENV TERM xterm

# install Node.js
COPY --from=node /usr/local/bin /usr/local/bin
COPY --from=node /usr/local/lib /usr/local/lib

RUN apt-get update && \
    apt-get -y install \
    git \
    zip \
    procps \
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
RUN touch /var/www/log/task.log

# php fpmとcronの起動
# CMD cron -f && /usr/local/sbin/php-fpm
CMD service cron start && /usr/local/sbin/php-fpm
