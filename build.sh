#!/bin/bash

docker-compose build

docker-compose up -d

docker-compose exec app composer install

docker-compose exec app  php artisan horizon:publish

docker-compose exec app php artisan key:generate

docker-compose exec app php artisan l5-swagger:generate

docker-compose exec app php artisan migrate:refresh

docker-compose exec app php artisan db:seed

docker-compose exec app php artisan test

docker-compose exec app supervisord -c /etc/supervisord.conf & docker-compose exec app php artisan serve --host=0.0.0.0 --port=6001
