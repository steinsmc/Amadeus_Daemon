#!/usr/bin/env bash

cd /tmp
groupadd amadeus
useradd steinsmc
usermod -g amadeus steinsmc
mkdir /home/steinsmc/amadeus/
yum -y install vim git openssl* gcc gcc-c++ autoconf cmake libcurl* curl* *jpeg* *png* screen libicu* libxml2* wget tar zip unzip libzip-devel libyaml-devel
wget "http://us1.php.net/get/php-7.3.3.tar.gz/from/this/mirror" -O m
tar -zxvf m
cd php-7.3.3
./configure --prefix=/home/steinsmc/amadeus/library/ --disable-fpm --enable-mysqlnd --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd --with-iconv-dir --with-freetype-dir=/usr/local/freetype --with-zlib --with-libxml-dir=/usr --enable-xml --disable-rpath --enable-bcmath --enable-shmop --enable-sysvsem --enable-inline-optimization --with-curl=/usr/local/curl --enable-mbregex --enable-mbstring --enable-intl --enable-pcntl --enable-ftp --with-openssl --with-mhash --enable-pcntl --enable-sockets --with-xmlrpc --enable-soap --with-gettext --enable-fileinfo --enable-opcache --with-libdir=lib64
make -j8
make install
ln -s /home/steinsmc/amadeus/library/bin/php /usr/bin/php
ln -s /home/steinsmc/amadeus/library/bin/pecl /usr/bin/pecl
ln -s /home/steinsmc/amadeus/library/bin/phpize /usr/bin/phpize
ln -s /home/steinsmc/amadeus/library/bin/php-config /usr/bin/php-config
mv php.ini-development /home/steinsmc/amadeus/library/bin/php.ini
pecl install swoole
pecl install yaml
cd /tmp
rm -rf m
git clone https://github.com/lixworth/Amadeus_Daemon.git
cd Amadeus_Daemon
php -r "file_put_contents('/home/steinsmc/amadeus/library/bin/php.ini','extension=swoole.so'.PHP_EOL,FILE_APPEND);"
php -r "file_put_contents('/home/steinsmc/amadeus/library/bin/php.ini','extension=yaml.so'.PHP_EOL,FILE_APPEND);"
curl https://getcomposer.org/installer | php
php composer.phar install
sh build.sh
cd build
cp -r Amadeus.phar /home/steinsmc/amadeus/library/Amadeus.phar
chown -R steinsmc:amadeus /home/steinsmc/
cd /home/steinsmc/amadeus/
su steinsmc
php Amadeus.phar