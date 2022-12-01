init: init-ci 
	
init-ci: docker-down-clear \
	docker-pull docker-build docker-up \

up: docker-up
down: docker-down
restart: down up

run:
	docker compose run --rm test-php-cli php app/index.php ${HOST}

images:
	docker images

prune:
	docker system prune -af --volumes

memory:
	sudo sh -c "echo 3 > /proc/sys/vm/drop_caches"

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build --pull
