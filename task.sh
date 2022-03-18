#!/bin/sh
# echo "[`date`] Hello!" >> /var/log/cron.
python3 /var/www/html/app/python/test.py >> /var/log/cron.log
