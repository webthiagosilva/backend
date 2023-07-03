bash:
	docker exec -it app bash

bash-root:
	docker exec -it -u root app bash

test:
	docker exec --network=none -it app bash -c "vendor/bin/phpunit"

test-filter:
	docker exec --network=none -it app bash -c "vendor/bin/phpunit --filter $(filter)"

stan:
	docker exec -it app bash -c "vendor/bin/phpstan analyse -c phpstan.neon"
