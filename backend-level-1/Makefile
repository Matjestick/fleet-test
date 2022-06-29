PROJECT_DIR := $(abspath $(dir $(lastword $(MAKEFILE_LIST))))
DOCKER_COMPOSE=docker-compose -f $(PROJECT_DIR)/docker-compose.yaml
DCR=$(DOCKER_COMPOSE) run --rm

vendor: composer.json
	docker-compose run --rm app composer install
down:
	$(DOCKER_COMPOSE) down --remove-orphans
run: down vendor
	$(DOCKER_COMPOSE) up -d
shell:
	$(DCR) app bash
behat: vendor
	$(DCR) app vendor/bin/behat