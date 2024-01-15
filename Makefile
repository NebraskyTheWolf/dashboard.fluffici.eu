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

	docker compose exec akce cp .env.example .env
	docker compose exec akce php artisan key:generate
	docker compose exec akce php artisan storage:link
	docker compose exec akce chmod -R 777 storage bootstrap/cache

	docker compose exec shop cp .env.example .env
	docker compose exec shop php artisan key:generate
	docker compose exec shop php artisan storage:link
	docker compose exec shop chmod -R 777 storage bootstrap/cache

	docker compose exec www cp .env.example .env
	docker compose exec www php artisan key:generate
	docker compose exec www php artisan storage:link
	docker compose exec www chmod -R 777 storage bootstrap/cache
	@make fresh
build:
	docker-compose build --build-arg GIT_COMMIT=$(GIT_SHA_FETCH)

revision:
	@echo $(GIT_SHA_FETCH)

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
	docker compose exec www php artisan migrate
	docker compose exec shop php artisan migrate
	docker compose exec akce php artisan migrate
fresh:
	docker compose exec dashboard php artisan migrate:fresh --seed
	docker compose exec www php artisan migrate:fresh --seed
	docker compose exec shop php artisan migrate:fresh --seed
	docker compose exec akce php artisan migrate:fresh --seed
seed:
	docker compose exec dashboard php artisan db:seed
	docker compose exec www php artisan db:seed
	docker compose exec shop php artisan db:seed
	docker compose exec akce php artisan db:seed
optimize:
	docker compose exec dashboard php artisan optimize
	docker compose exec www php artisan optimize
	docker compose exec shop php artisan optimize
	docker compose exec akce php artisan optimize
optimize-clear:
	docker compose exec dashboard php artisan optimize:clear
	docker compose exec www php artisan optimize:clear
	docker compose exec shop php artisan optimize:clear
	docker compose exec akce php artisan optimize:clear
db:
	docker compose exec db bash
sql:
	docker compose exec db bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'
redis:
	docker compose exec redis redis-cli

artisan-serve:
	php ./src/artisan serve

createbuckets-win:
	./bin/mc.exe alias set mycloud http://localhost:9000
	./bin/mc.exe mb minio/attachments
	./bin/mc.exe mb minio/avatars
	./bin/mc.exe mb minio/photos
	./bin/mc.exe mb minio/banners

createbuckets-linux:
	chmod +x ./bin/mc
	./bin/mc alias set mycloud http://localhost:9000
	./bin/mc mb minio/attachments
	./bin/mc mb minio/avatars
	./bin/mc mb minio/photos
	./bin/mc mb minio/banners

ping-minio:
	./bin/mc.exe alias set mycloud http://localhost:9000
	./bin/mc.exe ping mycloud --error-count 20 --count 10 --interval 300

ping-minio-linux:
	chmod +x ./bin/mc
	./bin/mc alias set mycloud http://localhost:9000
	./bin/mc ping mycloud --error-count 20 --count 10 --interval 300

buckets:
	./bin/mc.exe alias set mycloud http://localhost:9000
	./bin/mc.exe ls

clean:
	cd ./src/dashboard
	php artisan config:clear
	php artisan config:cache
	php artisan cache:clear
	php artisan optimize
