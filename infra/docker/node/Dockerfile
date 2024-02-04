# specify the node base image with your desired version node:<version>
FROM node:16
USER root

RUN mkdir /workspace

WORKDIR /workspace

COPY ./node /workspace

RUN apt-get update  \
   && apt install -y python make g++ \
   && rm -rf /var/cache/apt/*

RUN cd /workspace \
    && npm install express -g \
    && npm install bwip-js -g \
    && npm install canvas -g \
    && npm install http -g \
    && npm install nodejs-base64-converter -g

CMD ["node", "index.js"]
