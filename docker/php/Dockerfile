FROM php:8.0-fpm
ENV TZ Asia/Tokyo

ENV LANG C.UTF-8
ENV LANGUAGE en_US

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# COPY php.ini
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# Composer install
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# コンテナ内を日本語入力に対応させる
RUN apt-get update && \
    apt-get install -y locales && \
    echo "ja_JP UTF-8" > /etc/locale.gen && \
    locale-gen && \
    echo "export LANG=ja_JP.UTF-8" >> ~/.bashrc

# install Node.js
COPY --from=node /usr/local/bin /usr/local/bin
COPY --from=node /usr/local/lib /usr/local/lib

RUN apt-get update && \
    apt-get -y install \
    git \
    curl \
    zip \
    procps \
    unzip \
    vim \
    busybox-static \
    && docker-php-ext-install pdo_mysql bcmath

RUN mkdir -p /var/www/log
RUN touch /var/www/log/task.log
RUN mkdir -p /var/spool/cron/crontabs && echo "*/1 * * * * cd /var/www/html && /usr/local/bin/php artisan schedule:run >> /var/www/log/task.log 2>&1" > /var/spool/cron/crontabs/root

# RUN mkdir -p /var/www/log
# RUN apt-get install -y cron
# RUN chmod 0644 /etc/cron.d/*
# RUN touch /var/www/log/task.log


# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www/html


# php fpmとcronの起動
# USER $user
CMD ["busybox", "crond", "-f", "-L", "/dev/stderr", "/usr/local/sbin/php-fpm"]

