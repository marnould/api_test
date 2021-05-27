FROM php:7.4-fpm

RUN apt-get update

RUN apt-get install -y libzip-dev zlib1g-dev libpq-dev git libicu-dev libxml2-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-install zip xml

RUN apt-get install -y unzip
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime
RUN "date"

#;This sets the maximum amount of memory in bytes that a script is allowed to allocate
RUN echo "memory_limit = 1G" >> /usr/local/etc/php/conf.d/docker-php-ext-intl.ini

#;The maximum size of an uploaded file.
RUN echo "upload_max_filesize = 40M" >> /usr/local/etc/php/conf.d/docker-php-ext-intl.ini

#;Sets max size of post data allowed. This setting also affects file upload. To upload large files, this value must be larger than upload_max_filesize
RUN echo "post_max_size = 60M" >> /usr/local/etc/php/conf.d/docker-php-ext-intl.ini

RUN echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=phpstorm-session1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=10000" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN echo "max_execution_time = 60" >> /usr/local/etc/php/conf.d/docker-php-ext-intl.ini

WORKDIR /var/www/symfony