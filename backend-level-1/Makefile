PROJECT_DIR := $(abspath $(dir $(lastword $(MAKEFILE_LIST))))
DOCKER_COMPOSE=docker-compose -f $(PROJECT_DIR)/docker-compose.yaml
DCR=$(DOCKER_COMPOSE) run --rm

down:
	$(DOCKER_COMPOSE) down --remove-orphans
run: down
	$(DOCKER_COMPOSE) up -d
shell:
	$(DCR) app bash