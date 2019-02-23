FROM php:7.0.18-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    mysql-client libmagickwand-dev curl git --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-install mcrypt pdo_mysql mbstring bcmath tokenizer zip

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN apt-get install wget -y --no-install-recommends
RUN wget https://phar.phpunit.de/phpunit-6.5.phar && \
    chmod +x phpunit-6.5.phar && \
    mv phpunit-6.5.phar /usr/local/bin/phpunit
