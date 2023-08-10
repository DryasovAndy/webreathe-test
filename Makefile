up:
	docker-compose up -d

down:
	docker-compose down

re:
	make down
	make up

build:
	docker-compose up -d --build

composer-install:
	docker-compose exec --user=www-data php composer install

bash:
	docker-compose exec --user=application php bash

cc:
	rm -rf app/var/cache/
	docker-compose exec -T --user=application php bin/console ca:cl

drop-db:
	docker-compose exec --user=application php bin/console doctrine:database:drop --force

create-db:
	docker-compose exec --user=application php bin/console doctrine:database:create --if-not-exists

migration:
	docker-compose exec -T --user=application php bin/console doctrine:migrations:migrate

install:
	make down
	make build
	docker-compose exec --user=www-data php composer install
	make drop-db
	make create-db
	make migration
	make cc

generate-measurements:
	docker-compose exec -T --user=application php bin/console webreathe:generate-measurements