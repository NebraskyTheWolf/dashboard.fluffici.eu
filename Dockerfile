FROM jkaninda/nginx-php-fpm:8.3 AS base

USER root

WORKDIR /var/www/html

RUN chown -R root:root /var/www/html

VOLUME /var/www/html/storage

# timezone environment
ENV TZ=UTC \
  # locale
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 \
  # composer environment
  COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

RUN <<EOF
  apt-get update
  apt-get -y install --no-install-recommends \
    locales \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev
  locale-gen en_US.UTF-8
  localedef -f UTF-8 -i en_US en_US.UTF-8

  composer config -g process-timeout 3600
  composer config -g repos.packagist composer https://packagist.org
EOF

FROM base AS development

RUN <<EOF
  apt-get -y install --no-install-recommends \
    default-mysql-client
  apt-get clean
  rm -rf /var/lib/apt/lists/*
EOF

COPY ./php.development.ini /usr/local/etc/php/php.ini
COPY ./config/zz-nolog.conf /usr/local/etc/php-fpm.d/zz-nolog.conf

FROM development AS development-xdebug

RUN <<EOF
  pecl install xdebug
  docker-php-ext-enable xdebug
EOF

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

FROM base AS deploy

COPY ./php.deploy.ini /usr/local/etc/php/php.ini

COPY ./src /var/www/html


RUN <<EOF
  chmod -R 777 storage bootstrap/cache
  mkdir storage/framework/cache
  mkdir storage/framework/sessions
  mkdir storage/framework/testing
  mkdir storage/framework/views
  mkdir storage/cache
  mkdir storage/app/public
  mkdir storage/logs
  php artisan optimize:clear
  php artisan optimize
  apt-get clean
  chmod -R 777 storage
  rm -rf /var/lib/apt/lists/*
EOF

RUN docker-php-ext-install mysqli pdo pdo_mysql
