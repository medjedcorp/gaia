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

# COPY ./docker/python/execron.sh .
# ENV TZ Asia/Tokyo

RUN apt-get update && apt-get install -y less cron
# COPY ./docker/python/crontab /etc/cron.d
COPY ./docker/python/task.sh /usr/local/bin/
COPY ./docker/python/crontab /etc/cron.d/
RUN chmod 0644 /etc/cron.d/crontab
# RUN chmod 0644 /etc/cron.d/*
RUN mkdir -p /var/www/log
RUN touch /var/www/log/cron.log

RUN apt-get update && \
    apt-get -y install \
    git \
    zip \
    unzip \
    curl \
    gnupg \
    wget \
    fonts-ipafont \
    fonts-ipaexfont \
    vim  

ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get install -y --no-install-recommends tzdata \
    && rm -rf /var/lib/apt/lists/*
RUN ln -fs /usr/share/zoneinfo/Asia/Tokyo /etc/localtime \
    && dpkg-reconfigure --frontend noninteractive tzdata

RUN apt-get update
RUN apt-get install -y python3 python3-pip
# RUN apt-get install wget
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
# RUN pip install --upgrade setuptools
# RUN pip install requests
RUN pip3 install -r /tmp/requirements.txt

# mysqlにつなぎたい場合
RUN pip install mysql-connector-python 

# RUN echo "* 8 * * * . /env.sh && /usr/local/bin/python3 /var/www/html/app/python/estate.py >> /var/www/log/cron.log 2>&1" > crontab.txt && crontab crontab.txt

# RUN echo "*/2 * * * * . /env.sh && /usr/local/bin/python3 /var/www/html/app/python/test.py >> /var/www/log/cron.log 2>&1" > crontab.txt && crontab crontab.txt

# RUN echo "* 8 * * * . /env.sh && /usr/local/bin/python3 /var/www/html/app/python/estate.py >> /var/www/log/cron.log 2>&1\n*/3 * * * * . /env.sh && /usr/local/bin/python3 /var/www/html/app/python/test.py >> /var/www/log/cron.log 2>&1\n" > crontab.txt && crontab crontab.txt

WORKDIR /var/www/html

# COPY ./docker/python/task.sh /usr/local/bin/
# RUN chmod 755 /usr/local/bin/task.sh

# CMD ["busybox", "crond", "-l", "8", "-L", "/dev/stderr", "-f"]
CMD ["cron", "-l", "8", "-L", "/dev/stderr", "-f"]
# CMD ["/bin/bash", "/execron.sh", "-l", "8", "-L", "/dev/stderr", "-f"]