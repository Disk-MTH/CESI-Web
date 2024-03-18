FROM php:apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite
COPY certs/server.crt /etc/ssl/certs/
COPY certs/server.key /etc/ssl/private/