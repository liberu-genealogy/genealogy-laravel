# Makefile for common docker tasks
.PHONY: build up down logs ps exec artisan

BUILD_ARGS := --build-arg WWWUSER=$(shell id -u) --build-arg WWWGROUP=$(shell id -g)

build:
	docker-compose build $(BUILD_ARGS)

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

logs:
	docker-compose logs -f

ps:
	docker-compose ps

exec:
	@echo "Usage: make exec SERVICE CMD='bash'"
	docker-compose exec $(SERVICE) $(CMD)

artisan:
	docker-compose exec app php artisan $(CMD)
