FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    libonig-dev \
    unzip \
    && docker-php-ext-install mbstring

RUN curl -L https://github.com/JamesHeinrich/getID3/archive/master.zip -o getid3.zip \
    && unzip getid3.zip -d /usr/src/php/ \
    && mv /usr/src/php/getID3-master /usr/src/php/getID3 \
    && rm getid3.zip

WORKDIR /var/www/html

COPY public /var/www/html

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]
