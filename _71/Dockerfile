FROM php:7.1.3-fpm
MAINTAINER Dmitry Kuzmin rockwith@me.com

RUN DEBIAN_FRONTEND=noninteractive \
	apt-get update;\
	apt-get -y install mc software-properties-common && \

#-------------LDAP---------------
    apt-get install -y libldap2-dev && \
	docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
	docker-php-ext-install ldap && \

#-------------OTHER_EXT----------------
    apt-get install -y --no-install-recommends \
    curl \
    libmemcached-dev \
    libz-dev \
    libjpeg-dev \
    libpng12-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev && \
    pecl channel-update pecl.php.net && \
    docker-php-ext-install mcrypt && \
	docker-php-ext-install zip && \
	docker-php-ext-install tokenizer && \
  	docker-php-ext-install pdo_mysql && \
  	docker-php-ext-configure gd \
    --enable-gd-native-ttf \
    --with-jpeg-dir=/usr/lib \
    --with-freetype-dir=/usr/include/freetype2 && \
    docker-php-ext-install gd && \

##---------------MSSQL----------------
    echo "\r\n deb http://ftp.de.debian.org/debian/ sid main" >> /etc/apt/sources.list && \
    apt-get update && \
    apt-get -y install tdsodbc php7.1-sybase gcc build-essential
# libsybdb5 мы соберём сами

ADD freetds-patched.tar /temp/
RUN cd /temp/freetds-1.00.27 && \
	./configure --enable-msdblib --prefix=/usr/local && \
	make -j$(nproc) && \
	make install && \
    docker-php-ext-install pdo_dblib && \
    sed -i '$ d' /etc/apt/sources.list



#-------------XDEBUG----------------

ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    # Install the xdebug extension
    pecl install xdebug && \
    docker-php-ext-enable xdebug \
;fi





ADD xdebug.ini /usr/local/etc/php/conf.d/
ADD php.ini /usr/local/etc/php/
ADD freetds.conf /usr/local/etc

CMD ["php-fpm"]
EXPOSE 9000
