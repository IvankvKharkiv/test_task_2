FROM nginx

ARG NGINX_HOST

COPY  docker/conf/vhost.conf /etc/nginx/conf.d/default.conf
RUN sed -i "s/NGINX_HOST/${NGINX_HOST}/g" /etc/nginx/conf.d/default.conf

RUN apt-get update
RUN apt install nano -y

WORKDIR /var/www/${NGINX_HOST}