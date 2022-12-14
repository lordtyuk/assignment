FROM php:7.4-fpm

#dockerfile
RUN cd /usr/local/etc/php/conf.d/ && \
  echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini
ENV DEBIAN_FRONTEND noninteractive
COPY wait-for-it.sh /usr/bin/wait-for-it
ENV COMPOSER_MEMORY_LIMIT=2G
RUN chmod +x /usr/bin/wait-for-it

RUN apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng-dev zlib1g-dev libxml2-dev libonig-dev graphviz \
    build-essential \
    xorg \
    libssl-dev \
    libxrender-dev \
    wget \
    cron \
    gdebi && \
    pecl install apcu && \
    docker-php-ext-install zip && \
    pecl install mcrypt-1.0.4 && \
    docker-php-ext-enable mcrypt && \
    docker-php-ext-install soap && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql gd && \
    docker-php-ext-enable apcu pdo_pgsql sodium gd && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/backend

CMD cd /var/www/backend && chmod 777 var/cache/ && echo 'composer start' && composer i -o && echo 'composer done' && chmod +x bin/console && echo 'console' && echo 'schema updated' && php bin/console doctrine:migrations:migrate --no-interaction && echo 'migrated' && php bin/console app:read-xlsx local  && echo 'xls-processed' && rm -Rf var/cache/dev && rm -Rf var/cache/prod && rm -Rf var/log/*.log && php-fpm

EXPOSE 9000
