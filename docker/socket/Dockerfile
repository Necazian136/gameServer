FROM php:7.3-fpm

RUN apt-get update
RUN apt-get install -y git
RUN apt-get install -y curl
RUN apt-get install -y wget
RUN apt-get install -y libcurl4-openssl-dev
RUN docker-php-ext-install curl
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install exif
RUN docker-php-ext-install sockets
RUN docker-php-ext-install pdo_mysql

CMD cd /var && mkdir server

WORKDIR /var/server/bin

EXPOSE 8080

CMD php server.php