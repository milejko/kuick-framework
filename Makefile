PHP_VERSION ?= 8.5
OS_VERSION ?= noble
IMAGE_NAME := kuickphp/kuick

.DEFAULT_GOAL := test
.PHONY: * # ignore files named like targets

test:
	# generate CI_TAG to avoid concurrent run collisions
	$(eval CI_TAG := $(IMAGE_NAME):$(PHP_VERSION)-$(OS_VERSION)-$(shell date +%s%N))
	docker build --build-arg=PHP_VERSION=$(PHP_VERSION) --build-arg=OS_VERSION=$(OS_VERSION) --tag $(CI_TAG) .
	docker run --rm -v ./:/var/www/html $(CI_TAG) sh -c "composer up && composer test:all"
	docker image rm $(CI_TAG)

console:
	docker build --build-arg=PHP_VERSION=$(PHP_VERSION) --build-arg=OS_VERSION=$(OS_VERSION) --tag $(IMAGE_NAME) .
	docker run --rm -it -v ./:/var/www/html $(IMAGE_NAME) sh -c "composer install && bash"
	docker image rm $(IMAGE_NAME)
