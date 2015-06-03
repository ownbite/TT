FROM wordpress:4.2-fpm
MAINTAINER Linus Wallin version: 0.1

RUN apt-get update
RUN apt-get install git -y

# RUN git clone https://github.com/henrikhelsingborg/TT.git
