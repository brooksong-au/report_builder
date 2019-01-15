FROM brooksong/php:7.3
WORKDIR /app

RUN a2enmod rewrite
ADD docker_composes/000-default.conf /etc/apache2/sites-available/000-default.conf
ADD docker_composes/apache2.conf /etc/apache2/apache2.conf
ADD docker_composes/php.ini /usr/local/etc/php/php.ini

EXPOSE 80