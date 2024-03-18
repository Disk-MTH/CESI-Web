FROM php:apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite && a2enmod headers && service apache2 restart