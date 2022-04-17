FROM ubuntu:20.04
ENV TZ Asia/Tokyo
RUN apt-get update && apt-get install -y busybox-static

RUN mkdir -p /var/www/log
RUN touch /var/www/log/cron.log

RUN apt-get update && \
    apt-get -y install \
    git \
    zip \
    unzip \
    curl \
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
RUN wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add linux_signing_key.pub
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
RUN pip3 install -r /tmp/requirements.txt

WORKDIR /var/www/html

COPY ./docker/busybox/task.sh /usr/local/bin/
RUN chmod 755 /usr/local/bin/task.sh

CMD ["busybox", "crond", "-l", "8", "-L", "/dev/stderr", "-f"]
