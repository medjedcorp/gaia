FROM nginx:1.19-alpine

ENV TZ Asia/Tokyo

RUN apk update && \
  apk add --update --no-cache --virtual=.build-dependencies g++

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html
