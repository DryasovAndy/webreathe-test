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
	docker-compose exec -T --user=application php bin/console doctrine:migrations:migrate --no-interaction

install:
	make down
	make build
	make permissions
	docker-compose exec --user=www-data php composer install
	make drop-db
	make create-db
	make migration
	make cc
	make database-permissions

permissions:
	sudo chown 1000:www-data -R ./
	sudo chown 1000:1000 -R docker/mysql_database/
	sudo chmod 775 -R ./
	sudo chmod 777 -R docker/mysql_database/

database-permissions:
	sudo chown 1000:www-data -R ./
	sudo chown 1000:1000 -R docker/mysql_database/
	sudo chmod 777 -R docker/mysql_database/

generate-measurements:
	docker-compose exec -T --user=application php bin/console webreathe:generate-measurements