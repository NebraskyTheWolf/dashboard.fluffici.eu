GIT_SHA_FETCH := $(shell git rev-parse HEAD)
export GIT_SHA=$(GIT_SHA_FETCH)

install:
	@make build
	@make up
	cd /workspace
	docker compose exec dashboard cp .env.example .env
	docker compose exec dashboard php artisan key:generate
	docker compose exec dashboard php artisan storage:link
	docker compose exec dashboard chmod -R 777 storage bootstrap/cache
	@make fresh
build:
	docker-compose build --build-arg GIT_COMMIT=$(GIT_SHA_FETCH)

routes:
	docker compose exec dashboard php artisan route:list

make-user:
	docker compose exec dashboard php artisan orchid:admin

revision:
	@echo $(GIT_SHA_FETCH)

status:
	docker compose exec dashboard php artisan migrate:status

make-report:
	docker compose exec dashboard php artisan app:generate-monthly-report

finish-old-event:
	docker compose exec dashboard php artisan app:finish-old-event

up:
	docker compose up -d
stop:
	docker compose stop
down:
	docker compose down --remove-orphans
down-v:
	docker compose down --remove-orphans --volumes
restart:
	@make down
	@make up
destroy:
	docker compose down --rmi all --volumes --remove-orphans
remake:
	@make destroy
	@make install
reload:
	@make down
	@make install
	@make up
ps:
	docker compose ps
migrate:
	docker compose exec dashboard php artisan migrate
fresh:
	docker compose exec dashboard php artisan migrate:fresh --seed
seed:
	docker compose exec dashboard php artisan db:seed
optimize:
	docker compose exec dashboard php artisan optimize
optimize-clear:
	docker compose exec dashboard php artisan optimize:clear

clear-config:
	docker compose exec dashboard php artisan config:clear

db:
	docker compose exec db bash
sql:
	docker compose exec db bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'

clean:
	cd ./src/dashboard
	php artisan config:clear
	php artisan config:cache
	php artisan cache:clear
	php artisan optimize
