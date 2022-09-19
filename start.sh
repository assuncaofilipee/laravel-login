#!/bin/sh
sleep 15s

sudo composer insall

sudo php artisan horizon:publish

sudo php artisan config:cache

sudo php artisan cache:clear

sudo composer dumpautoload

sudo php artisan l5-swagger:generate

sudo php artisan migrate

sudo php artisan serve --host=0.0.0.0 --port=80 & sudo supervisord -c /etc/supervisord.conf
