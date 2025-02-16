import:
	docker compose exec -it bulls-media.test php artisan app:xls
start:
	docker compose up -d
stop:
	docker compose down
sh:
	docker compose exec -it bulls-media.test bash
test:
	docker compose exec -it bulls-media.test php artisan test
init:
	make start
	docker compose exec -it bulls-media.test composer install
	make generate-key
	docker compose exec -it bulls-media.test php artisan migrate:install
	docker compose exec -it bulls-media.test php artisan migrate
generate-key:
	docker compose exec -it bulls-media.test php artisan key:generate
remove-all-data:
	docker compose down -v
