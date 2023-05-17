ARG NGINX_VERSION=1.23.3

FROM nginx:${NGINX_VERSION}-alpine as base

FROM nginx:${NGINX_VERSION}-alpine as prod

WORKDIR /var/www/html

COPY . .

COPY ./docker/nginx/default.prod.conf /etc/nginx/conf.d/default.conf

RUN apk add --update \
    nodejs \
    npm

RUN npm install

RUN NODE_ENV=production npx tailwindcss -i src/css/styles.css -o public/css/styles.css --minify
