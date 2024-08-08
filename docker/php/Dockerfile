FROM php:8.2-fpm

ENV DEBIAN_FRONTEND=noninteractive

RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
            curl \
            git \
            zip \
            zlib1g-dev \
            libzip-dev \
            libssl-dev \
            libmcrypt-dev \
            libmariadb-dev; \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip
RUN docker-php-ext-install zip pdo pdo_mysql
#RUN pecl install mongodb && docker-php-ext-enable mongodb
#RUN echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN  mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /srv

#ENTRYPOINT ["/docker-entrypoint"]

#RUN echo "memory_limit=1024M" > $PHP_INI_DIR/conf.d/cumstom.ini

#CMD ["php-fpm", "-R"]