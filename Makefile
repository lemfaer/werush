help:    ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

init:
	$(MAKE) up
	$(MAKE) install
	$(MAKE) migrate

mysql:   ## Run mysql cli from docker container using root.
	@sudo mysql -h 127.0.0.1 -P 33061 -u root

exec:    ## Run composer using php from docker container.
	@sudo docker-compose exec php sh

install: ## Run composer install using php from docker container.
	@sudo docker-compose exec php composer install

migrate: ## Run doctrine migrations using php from docker container.
	@sudo docker-compose exec php php bin/console doctrine:migrations:migrate

build:   ## [Docker] Services are built once and then tagged.
	@sudo docker-compose build

up:      ## [Docker] Builds, (re)creates, starts, and attaches to containers for a service (in the background).
	@sudo docker-compose up -d

down:    ## [Docker] Stops containers and removes containers, networks, volumes, and images created by up.
	@sudo docker-compose down

start:   ## [Docker] Starts existing containers for a service.
	@sudo docker-compose start

stop:    ## [Docker] Stops running containers without removing them.
	@sudo docker-compose stop

list:    ## [Docker] List containers.
	@sudo docker ps -a
