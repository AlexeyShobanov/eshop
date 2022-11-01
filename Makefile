setup:
	composer install
	cp -n .env.example .env
	php artisan key:gen --ansi

serve:
	php artisan serve --host spo.test

migrate:
	php artisan migrate

refresh:
	php artisan eshop:refresh

console:
	php artisan tinker

.PHONY: db
db:
	docker-compose up

test:
	php artisan config:clear
	php artisan test

lint:
	composer phpcs -- --standard=PSR12 app routes tests

lint-fix:
	composer phpcbf app/Http/Controllers tests

logs:
	tail -f storage/logs/laravel.log

production-logs:
	heroku logs -t

clear:
	php artisan route:clear
	php artisan cache:clear
	php artisan view:clear
	php artisan config:clear
	composer dump-autoload
