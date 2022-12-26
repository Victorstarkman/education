FROM php:7.4-apache

RUN apt-get update && \
    apt-get upgrade -y 
RUN docker-php-ext-install mysqli pdo pdo_mysql && \
    docker-php-ext-enable pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs
RUN service apache2 restart
# Timezone
ENV TZ 'America/Sao_Paulo'
RUN echo $TZ > /etc/timezone
RUN rm /etc/localtime
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime
RUN dpkg-reconfigure -f noninteractive tzdata

WORKDIR /var/www
RUN apt-get install -y git
RUN apt-get install -y nano
COPY ./Files /var/www/filesBot
RUN chown -R www-data:www-data /var/www/filesBot
RUN adduser --disabled-password romulo
RUN chown -R romulo:romulo /var/www
USER romulo:romulo