FROM php:5.6.30-fpm
MAINTAINER Dmitry Kuzmin rockwith@me.com

RUN DEBIAN_FRONTEND=noninteractive \
	apt-get update;\
	apt-get -y install mc;\
	apt-get -y install software-properties-common

#-------------MSSQL---------------
RUN echo "deb http://ftp.debian.org/debian/ testing non-free contrib main" >> /etc/apt/sources.list
RUN apt-get update
RUN apt-get -y install tdsodbc php5-sybase 
# libsybdb5 мы соберём сами
RUN apt-get -y install gcc
RUN apt-get -y install build-essential
ADD freetds-patched.tar /temp/
RUN cd /temp/freetds-1.00.27 && \
	./configure --enable-msdblib --prefix=/usr/local && \
	make && \
	make install
RUN docker-php-ext-install mssql pdo_dblib #можно и без mssql
#уберём за собой
RUN sed -i '$ d' /etc/apt/sources.list

#-------------LDAP---------------

RUN apt-get update && \ 
	apt-get install -y libldap2-dev && \
	docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
	docker-php-ext-install ldap

#-------------OTHER_EXT----------------

RUN apt-get install -y --no-install-recommends \
    curl \
    libmemcached-dev \
    libz-dev \
    libjpeg-dev \
    libpng12-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev

RUN docker-php-ext-install mcrypt && \
    #comment xdebug for prod
	pecl install xdebug && docker-php-ext-enable xdebug && \
	pecl install zip && docker-php-ext-enable zip && \
	pecl install memcached-2.2.0 && docker-php-ext-enable memcached && \
	docker-php-ext-install tokenizer && \
  	docker-php-ext-install pdo_mysql && \
  	docker-php-ext-configure gd \
    --enable-gd-native-ttf \
    --with-jpeg-dir=/usr/lib \
    --with-freetype-dir=/usr/include/freetype2 && \
    docker-php-ext-install gd


CMD ["php-fpm"]
EXPOSE 9000