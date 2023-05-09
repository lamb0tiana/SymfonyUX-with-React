FROM php:8.1.1-apache
ARG gitemail=tiana.randrianarivony@gmail.com
ARG gituser=tiana
WORKDIR /var/www/html
RUN apt-get update \
    &&  apt-get install -y --no-install-recommends \
        locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip libpq-dev nodejs npm wget \
        apt-transport-https lsb-release ca-certificates vim build-essential libsasl2-dev libssl-dev python-is-python3 zlib1g-dev gnupg2
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs && apt-get install -y yarn

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen  \
    &&  echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
    &&  locale-gen

RUN curl -sS https://getcomposer.org/installer | php -- \
    &&  mv composer.phar /usr/local/bin/composer

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' |  bash &&  apt install symfony-cli
RUN git config --global user.email ${gitemail} \
    &&  git config --global user.name ${gituser}


RUN docker-php-ext-configure \
            intl \
    &&  docker-php-ext-install \
            pdo pdo_mysql pdo_pgsql opcache intl zip calendar dom mbstring gd xsl

RUN pecl install apcu xdebug && docker-php-ext-enable apcu xdebug

RUN a2enmod rewrite
WORKDIR /var/www/html
CMD ["/usr/sbin/apache2ctl", "-D","FOREGROUND"]

#COPY  . .
#
#RUN chmod 755 entrypoint.sh