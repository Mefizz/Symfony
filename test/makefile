.PHONY: migrate fixtures up

up:
	docker-compose up -d --build

migrate:
	docker-compose exec php vendor/bin/phinx migrate

fixtures:
	docker-compose exec php bin/console doctrine:fixtures:load --no-interaction

cache-clear:
	docker-compose exec php php bin/console cache:clear
