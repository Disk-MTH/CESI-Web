FROM php:apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite && a2enmod headers
RUN chmod -R 777 /var/www/