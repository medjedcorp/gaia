FROM python:3
USER root
# seleniumをインストール

RUN apt-get update && apt-get -y install locales && localedef -f UTF-8 -i ja_JP ja_JP.UTF-8
ENV LANG ja_JP.UTF-8
ENV LANGUAGE ja_JP:ja
ENV LC_ALL ja_JP.UTF-8
ENV TZ JST-9
ENV TERM xterm

RUN mkdir -p /var/www/log
RUN touch /var/www/log/cron.log

RUN apt-get update && \
    apt-get -y install \
    less \
    git \
    zip \
    unzip \
    curl \
    gnupg \
    wget \
    fonts-ipafont \
    fonts-ipaexfont \
    vim \ 
    busybox-static

COPY ./docker/python/task.sh /usr/local/bin/
COPY ./docker/python/hello.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/task.sh
RUN chmod +x /usr/local/bin/hello.sh

COPY --chown=root:root ./docker/python/crontab /var/spool/cron/crontabs/root
# COPY ./docker/python/crontab /var/spool/cron/crontabs/root

# RUN apt-get update
# RUN apt-get install -y python3 python3-pip
# RUN apt-get update && apt-get install -y wget
# RUN apt-get install -y gnupg

COPY ./docker/python/requirements.txt /tmp/requirements.txt
RUN pip3 install --upgrade pip
RUN pip3 install --upgrade setuptools
RUN pip install --upgrade pip setuptools requests schedule
RUN pip install pipenv
RUN pip3 install --no-cache-dir -r /tmp/requirements.txt

# mysqlにつなぎたい場合
RUN pip install mysql-connector-python 
RUN pip install slackweb
# RUN pip install psutil

RUN mkdir -p /var/www/log
RUN touch /var/www/log/cron.log

WORKDIR /var/www/html

CMD ["busybox", "crond", "-l", "8", "-L", "/dev/stderr", "-f"]
