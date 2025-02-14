FROM php:7.2-apache
COPY index.php /var/www/html/index.php
COPY inject.php /var/www/html/inject.php
COPY error.php /var/www/html/error.php
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli