FROM wordpress:4.2-fpm
MAINTAINER Linus Wallin version: 0.1

RUN apt-get update

COPY ./wp-content /var/www/html/wp-content/
