GIT_SHA_FETCH := $(shell git rev-parse HEAD)
export GIT_SHA=$(GIT_SHA_FETCH)

build:
	docker build --build-arg GIT_SHA=$(GIT_SHA_FETCH) . -t ghcr.io/fluffici/dashboard:latest
	docker push ghcr.io/fluffici/dashboard:latest

buildtwo:
	docker build --file Dockerfile.sched . -t ghcr.io/fluffici/scheduler:latest
	docker push ghcr.io/fluffici/scheduler:latest

