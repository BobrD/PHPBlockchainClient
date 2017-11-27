FROM php:7.0.25-cli

RUN \
    apt-get update && \
    apt-get install -y --no-install-recommends git zip

RUN curl --silent --show-error https://getcomposer.org/installer | php

RUN mv composer.phar /usr/local/bin/composer

RUN pecl install xdebug

RUN docker-php-ext-enable xdebug \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_connect_back=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN groupadd -g 1000 user
RUN useradd -lm -u 1000 -g 1000 user

USER 1000

COPY php.ini /usr/local/etc/php

WORKDIR /var/www/blockchain.dev