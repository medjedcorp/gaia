FROM php:7.4.1-fpm

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

ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get install -y --no-install-recommends tzdata \
    && rm -rf /var/lib/apt/lists/*
RUN ln -fs /usr/share/zoneinfo/Asia/Tokyo /etc/localtime \
    && dpkg-reconfigure --frontend noninteractive tzdata

RUN apt-get update
RUN apt-get install -y python3 python3-pip
RUN apt-get install wget
RUN apt-get install -y gnupg

# install google chrome
RUN wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add -
RUN sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google-chrome.list'
RUN apt-get -y update
RUN apt-get install -y google-chrome-stable

# install chromedriver
RUN apt-get install -yqq unzip
RUN wget -O /tmp/chromedriver.zip http://chromedriver.storage.googleapis.com/`curl -sS chromedriver.storage.googleapis.com/LATEST_RELEASE`/chromedriver_linux64.zip
RUN unzip /tmp/chromedriver.zip chromedriver -d /usr/local/bin/

COPY ./requirements.txt /tmp/requirements.txt
RUN pip3 install --upgrade pip
RUN pip3 install --upgrade setuptools
RUN pip install --upgrade setuptools
# mysqlにつなぎたい場合
# RUN pip install mysql-connector-python 
RUN pip3 install -r /tmp/requirements.txt

RUN mkdir -p /var/www/log
RUN apt-get install -y cron
COPY task.sh /usr/local/bin/
COPY ./cron_task /etc/cron.d
RUN chmod 0644 /etc/cron.d/
CMD ["cron", "-f"]

# nginxエラー回避
CMD /usr/local/sbin/php-fpm

WORKDIR /var/www/html