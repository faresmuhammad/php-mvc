FROM php:8.1-apache

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

#Enabling Apache rewrite mode 'mod_rewrite' for url rewriting
RUN a2enmod rewrite

RUN apt-get update -y && \
    apt-get install \
    libzip-dev \
    wget \
    git \
    unzip \
    -y

#Installing php extensions
RUN docker-php-ext-install pdo pdo_mysql

COPY ./install-composer.sh ./

RUN sh ./install-composer.sh \
    && rm ./install-composer.sh

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www

CMD ["apache2-foreground"]