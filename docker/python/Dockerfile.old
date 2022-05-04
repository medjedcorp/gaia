FROM python:3-slim
USER root

RUN apt-get update \
  && apt-get -y install locales \
  && localedef -f UTF-8 -i ja_JP ja_JP.UTF-8
ENV LANG ja_JP.UTF-8
ENV LANGUAGE ja_JP:ja
ENV LC_ALL ja_JP.UTF-8
ENV TZ JST-9
ENV TERM xterm

# RUN apt-get update && apt-get install -y cron
# COPY ./docker/python/task.sh /usr/local/bin/
# COPY ./docker/python/hello.sh /usr/local/bin/
# RUN chmod 755 /usr/local/bin/task.sh
# RUN chmod 755 /usr/local/bin/hello.sh
# COPY ./docker/python/crontab /etc/cron.d/
# RUN chmod 0644 /etc/cron.d/crontab
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

ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get install -y --no-install-recommends tzdata \
    && rm -rf /var/lib/apt/lists/*
RUN ln -fs /usr/share/zoneinfo/Asia/Tokyo /etc/localtime \
    && dpkg-reconfigure --frontend noninteractive tzdata

RUN apt-get update
RUN apt-get install -y python3 python3-pip
RUN apt-get update && apt-get install -y wget
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

COPY ./docker/python/requirements.txt /tmp/requirements.txt
RUN pip3 install --upgrade pip
RUN pip3 install --upgrade setuptools
RUN pip install --upgrade pip setuptools requests schedule
RUN pip3 install -r /tmp/requirements.txt

# mysqlにつなぎたい場合
RUN pip install mysql-connector-python 
RUN pip install slackweb
RUN pip install psutil
#RUN pip install --no-cache-dir supervisor

# RUN mount -t tmpfs -o rw,nosuid,nodev,noexec,relatime,size=512M,uid=0,gid=0 tmpfs /dev/shm
# COPY ./docker/python/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

# CMD ["supervisord", "-c", "./supervisord.conf"]
# CMD ["cron", "-f"]
# CMD ["cron", "-l", "8", "-L", "/dev/stderr", "-f"]
CMD ["busybox", "crond", "-l", "8", "-L", "/dev/stderr", "-f"]