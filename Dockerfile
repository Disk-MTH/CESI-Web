FROM php:apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite && a2enmod headers
RUN mkdir -m 777 "/var/www/logs"
RUN mkdir -m 777 "/var/www/cache"
RUN mkdir -m 777 "/var/www/cache/twig"
RUN mkdir -m 777 "/var/www/cache/doctrine"