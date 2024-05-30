FROM php:8.3-fpm
MAINTAINER George Turetchi

ENV DEBIAN_FRONTEND noninteractive
#Xdebug env variables
ARG XDEBUG_REMOTE_HOST
ARG XDEBUG_REMOTE_PORT
ARG XDEBUG_IDE_KEY
ENV XDEBUG_REMOTE_HOST ${XDEBUG_REMOTE_HOST}
ENV XDEBUG_REMOTE_PORT ${XDEBUG_REMOTE_PORT}
ENV XDEBUG_IDE_KEY ${XDEBUG_IDE_KEY}

# ------------------------------
# install usefull binaries
# ------------------------------
RUN apt-get -yqq update && \
    apt-get install -yqq --no-install-recommends git imagemagick libmagickwand-dev openssh-client \
                                                 unzip libpcre3 libfcgi0ldbl libmemcached-dev \
                                                 libpcre3-dev libssl-dev \
                                                 jq vim zip libzip-dev \
                                                 wget curl gnupg sudo libfreetype6-dev

# ------------------------------
# install PHP extensions
# ------------------------------
RUN docker-php-ext-install exif intl opcache pdo_mysql mysqli bcmath && \
    # docker-php-ext-install soap && \
    docker-php-ext-install zip && \
    # redis
    pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

ENV current_os=linux

# Include the config files
COPY symfony.ini /usr/local/etc/php/conf.d/
COPY symfony.pool.conf /usr/local/etc/php-fpm.d/
COPY alias.sh /etc/profile.d/

# Include the start script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh


# Perform the composer update
#WORKDIR /Users/gturetchi/Documents/Projects/frinteza/laravel

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm -rf /tmp/*

CMD ["start.sh"]

#CMD npm install && npm run build
