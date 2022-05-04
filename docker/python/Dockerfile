FROM python:3
USER root
# seleniumをインストール

RUN apt-get update && \
    apt-get -y install \
    curl \
    vim \ 
    busybox-static

COPY ./docker/python/task.sh /usr/local/bin/
COPY ./docker/python/hello.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/task.sh
RUN chmod +x /usr/local/bin/hello.sh

COPY --chown=root:root ./docker/python/crontab /var/spool/cron/crontabs/root

COPY ./docker/python/requirements.txt /tmp/requirements.txt
RUN pip3 install --upgrade pip
RUN pip3 install --upgrade setuptools
RUN pip install --upgrade pip setuptools requests schedule
RUN pip install pipenv
RUN pip3 install --no-cache-dir -r /tmp/requirements.txt

# mysqlにつなぎたい場合
RUN pip install mysql-connector-python 
RUN pip install slackweb
RUN pip install psutil

RUN mkdir -p /var/www/log
RUN touch /var/www/log/cron.log

WORKDIR /var/www/html

CMD ["busybox", "crond", "-l", "8", "-L", "/dev/stderr", "-f"]
