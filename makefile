.DEFAULT_GOAL:=help
.PHONY: help up rm reset install-db build check_docker_file
DOCKER_COMPOSE_FILE="./docker-compose.yml"

help: ## Display this help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

check_docker_file:
	@test -f $(DOCKER_COMPOSE_FILE) || printf "Error \033[31m$(DOCKER_COMPOSE_FILE)\033[39m to install project no exist.\n"
	@exit

build: check_docker_file ## Build dockers containers of project
	docker-compose  -f $(DOCKER_COMPOSE_FILE) build

up: build ## Up dockers containers of project
	docker-compose  -f $(DOCKER_COMPOSE_FILE) up -d

rm: check_docker_file ## Remove containers of project
	docker-compose -f $(DOCKER_COMPOSE_FILE) stop
	docker-compose -f $(DOCKER_COMPOSE_FILE) rm -f

reset: rm up ## Reset containers of project
	docker-compose run php composer install
	sudo chown -fR $$UID:$$GID vendor/
	sleep 10
	docker-compose run php bin/console doctrine:schema:drop --force
	docker-compose run php bin/console doctrine:schema:create
	docker-compose run php bin/console doctrine:fixtures:load --no-interaction
	docker-compose run php bin/console fos:elastica:reset -eprod --no-debug
	#docker-compose run php bin/console snap:rooms:sync on bongacam -eprod --no-debug
	#docker-compose run php bin/console fos:elastica:populate -eprod --no-debug

cc: ## Cache Clear
	docker-compose run php bin/console cache:clear

cs: ## Fix files that need to be fixed
	./vendor/bin/php-cs-fixer fix ./src/ --verbose --allow-risky=yes --show-progress=estimating

deploy: ## Deploy to production
	bin/deploy

phpstan: ## Static analysis
	./vendor/bin/phpstan analyse -l 7 src

qa: cs phpstan ## static Quality Analysis

webpack-watch: ## to watch assets changes to be live rebuild (sync)
	npm run watch

webpack-production: ## to compile assets for production
	npm run production

deploy-production: ## to deploy in production
	ssh -t tracker "cd www.triel-solidaire.fr && bin/deploy"