# specify the node base image with your desired version node:<version>
FROM node:bookworm

RUN mkdir workspace

WORKDIR /workspace

COPY ./node /workspace

RUN npm install --save

CMD ["node", "index.js"]
