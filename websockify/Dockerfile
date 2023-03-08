FROM node:16.7.0-alpine3.12

COPY . /app
WORKDIR /app
RUN npm install

CMD ["node", "websockify.js"]