FROM python:3-slim
# Chrome & Webdriver
# RUN apt-get update -y && \
#     apt-get install -y --no-install-recommends chromium && \
#     pip install --upgrade pip setuptools wheel && \
#     pip install selenium && \
#     # webdriverはなるべく近いバージョンをダウンロード
#     pip install chromedriver-binary~=$(chromium --version | perl -pe 's/([^0-9]+)([0-9]+\.[0-9]+).+/$2/g')

# # 日本語環境
# ENV LANGUAGE ja_JP.UTF-8
# ENV LANG ja_JP.UTF-8
# RUN apt-get install -y --no-install-recommends locales && \
#     locale-gen ja_JP.UTF-8 && \
#     # 日本語フォントをインストール
#     apt-get install -y --no-install-recommends fonts-ipafont

ENV TZ Asia/Tokyo
RUN apt-get update && apt-get install -y busybox-static

RUN apt-get install -y cron
COPY ./docker/python/crontab /etc/cron.d
RUN chmod 0644 /etc/cron.d/*
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
RUN pip install --upgrade setuptools
RUN pip install requests
RUN pip3 install -r /tmp/requirements.txt

WORKDIR /var/www/html

COPY ./docker/python/task.sh /usr/local/bin/
RUN chmod 755 /usr/local/bin/task.sh

CMD ["busybox", "crond", "-l", "8", "-L", "/dev/stderr", "-f"]