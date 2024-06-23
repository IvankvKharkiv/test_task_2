FROM php:8.1-fpm

ARG NGINX_HOST

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt -yqq update
RUN apt -yqq install libxml2-dev
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install xml
RUN apt-get install -y libxslt1-dev
RUN docker-php-ext-install xsl

RUN apt-get update
RUN apt install mc -y
RUN apt install iproute2 -y
RUN apt install nano -y


RUN apt-get install -y p7zip \
    p7zip-full \
    unace \
    zip \
    unzip \
    xz-utils \
    sharutils \
    uudeview \
    mpack \
    arj \
    cabextract \
    file-roller \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/${NGINX_HOST}