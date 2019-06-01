COMPOSE_PROJECT_NAME=redkina

COMPOSE_FILE?=docker-compose.yml

BASE_DIR=$(PWD)

COMPOSE=docker-compose -p $(COMPOSE_PROJECT_NAME) -f $(COMPOSE_FILE)

build:
	$(COMPOSE) build

up:
	$(COMPOSE) pull
	$(COMPOSE) up -d $(COMPOSE_PROJECT_NAME)

down:
	$(COMPOSE) down

shell:
	$(COMPOSE) exec $(COMPOSE_PROJECT_NAME) /bin/sh

init: build up shell

clean:
	$(COMPOSE) kill
	$(COMPOSE) rm --force

redis:
	$(COMPOSE) exec redis redis-cli
