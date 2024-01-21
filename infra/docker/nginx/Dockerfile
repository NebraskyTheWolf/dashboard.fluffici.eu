FROM nginx:1.25

WORKDIR /workspace

ENV TZ=UTC

COPY ./infra/docker/nginx/*.conf /etc/nginx/conf.d/

ARG GIT_COMMIT
ENV GIT_COMMIT=$GIT_COMMIT
