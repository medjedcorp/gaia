FROM python:3-slim
# Chrome & Webdriver
RUN apt-get update -y && \
    apt-get install -y --no-install-recommends chromium && \
    pip install --upgrade pip setuptools wheel && \
    pip install selenium && \
    # webdriverはなるべく近いバージョンをダウンロード
    pip install chromedriver-binary~=$(chromium --version | perl -pe 's/([^0-9]+)([0-9]+\.[0-9]+).+/$2/g')

# 日本語環境
ENV LANGUAGE ja_JP.UTF-8
ENV LANG ja_JP.UTF-8
RUN apt-get install -y --no-install-recommends locales && \
    locale-gen ja_JP.UTF-8 && \
    # 日本語フォントをインストール
    apt-get install -y --no-install-recommends fonts-ipafont

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

COPY ./requirements.txt /tmp/requirements.txt
RUN pip3 install --upgrade pip
RUN pip3 install --upgrade setuptools
RUN pip install --upgrade setuptools
RUN pip3 install -r /tmp/requirements.txt

WORKDIR /var/www/html

COPY ./docker/selenium/task.sh /usr/local/bin/
RUN chmod 755 /usr/local/bin/task.sh

CMD ["busybox", "crond", "-l", "8", "-L", "/dev/stderr", "-f"]