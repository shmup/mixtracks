FROM php:8.1-cli

RUN apt-get update && apt-get install -y

WORKDIR /var/www/html

COPY public /var/www/html

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]
