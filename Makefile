#!make
include .env
export $(shell sed 's/=.*//' .env)

t=

up:
	docker-compose up
upd:
	docker-compose up -d
down:
	docker-compose down
downs:
	docker-compose down --remove-orphans
build:
	docker-compose up --build
buildd:
	docker-compose up --build -d
sh:
	docker exec -it laravel-login_app /bin/bash
db:
	docker exec -it trouw-mysql bash -c "mysql -u ${DB_USERNAME} -p'${DB_PASSWORD}' ${DB_DATABASE}"
migrate:
	php artisan migrate
install:
	composer install
dum:
	composer dump-autoload
cache:
	php artisan cache:clear && php artisan config:cache && php artisan route:clear && php artisan route:cache
reset:
	docker-compose down --remove-orphans && docker system prune -a -f && docker-compose up --build
