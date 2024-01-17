# Build Stage
FROM rustlang/rust:nightly-slim AS builder
USER 0:0
WORKDIR /home/rust/src

RUN USER=root cargo new --bin autumn
WORKDIR /home/rust/src/autumn
COPY  ./src/autumn/Cargo.toml ./src/autumn/Cargo.lock ./
COPY ./src/autumn/src ./src
RUN apt-get update && apt-get install -y libssl-dev pkg-config && cargo install --locked --path .

# Bundle Stage
FROM debian:bullseye-slim
RUN apt-get update && apt-get install -y ca-certificates ffmpeg
COPY --from=builder /usr/local/cargo/bin/autumn ./
EXPOSE 3000
ENV AUTUMN_HOST 0.0.0.0:3000
COPY  ./src/autumn/Autumn.toml ./

ARG GIT_COMMIT
ENV GIT_COMMIT=$GIT_COMMIT

CMD ["./autumn"]
