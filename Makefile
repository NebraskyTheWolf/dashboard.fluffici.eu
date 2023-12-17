.PHONY: install

install:
	php composer.phar install
	php composer.phar update
	php artisan migrate
