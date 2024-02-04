# specify the node base image with your desired version node:<version>
FROM node:16-bookworm
USER root

RUN mkdir /workspace

WORKDIR /workspace

COPY ./node /workspace

RUN apt install python make g++ \
   && rm -rf /var/cache/apt/*

RUN cd /workspace  \
    && npm install --save

CMD ["node", "index.js"]
