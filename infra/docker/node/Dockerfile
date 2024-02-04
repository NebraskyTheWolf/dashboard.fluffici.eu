# specify the node base image with your desired version node:<version>
FROM node:16-alpine
USER root

RUN mkdir /workspace

WORKDIR /workspace

COPY ./node /workspace



RUN cd /workspace  \
    && npm install --save

CMD ["node", "index.js"]
