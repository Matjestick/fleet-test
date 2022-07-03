include Makefile.qa.mk

PROJECT_DIR := $(abspath $(dir $(lastword $(MAKEFILE_LIST))))
DOCKER_COMPOSE=docker-compose -f $(PROJECT_DIR)/docker-compose.yaml
DCR=$(DOCKER_COMPOSE) run --rm
CONSOLE := $(DCR) app bin/console

vendor: composer.json
	$(DCR) --no-deps app composer install
down:
	$(DOCKER_COMPOSE) down --remove-orphans
run: down vendor
	$(DOCKER_COMPOSE) up -d app
shell:
	$(DCR) app bash
