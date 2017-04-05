FROM php:7.1.3-fpm
MAINTAINER Dmitry Kuzmin rockwith@me.com

RUN DEBIAN_FRONTEND=noninteractive \
	apt-get update;\
	apt-get -y install mc;\
	apt-get -y install software-properties-common

#-------------MSSQL---------------
RUN echo "deb http://ftp.debian.org/debian/ testing non-free contrib main" >> /etc/apt/sources.list
RUN apt-get update
RUN apt-get -y install tdsodbc php7.0-sybase 
# libsybdb5 мы соберём сами
RUN apt-get -y install gcc
RUN apt-get -y install build-essential
ADD freetds-patched.tar /temp/
RUN cd /temp/freetds-1.00.27 && \
	./configure --enable-msdblib --prefix=/usr/local && \
	make && \
	make install
RUN docker-php-ext-install pdo_dblib
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

RUN pecl channel-update pecl.php.net
RUN docker-php-ext-install mcrypt && \
	#comment xdebug for prod
	pecl install xdebug && docker-php-ext-enable xdebug && \
	docker-php-ext-install zip && \
	docker-php-ext-install tokenizer && \
  	docker-php-ext-install pdo_mysql && \
  	docker-php-ext-configure gd \
    --enable-gd-native-ttf \
    --with-jpeg-dir=/usr/lib \
    --with-freetype-dir=/usr/include/freetype2 && \
    docker-php-ext-install gd


#-------------MEMCACHE-----------------
RUN curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/php7.tar.gz" \
    && mkdir -p memcached \
    && tar -C memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
    && ( \
        cd memcached \
        && phpize \
        && ./configure \
        && make -j$(nproc) \
        && make install \
    ) \
    && rm -r memcached \
    && rm /tmp/memcached.tar.gz \
    && docker-php-ext-enable memcached 


CMD ["php-fpm"]
EXPOSE 9000