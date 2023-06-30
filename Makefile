make bash:
	docker exec -it $(CONTAINER_NAME) bash

make bash-root:
	docker exec -it -u root $(CONTAINER_NAME) bash