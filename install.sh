#!/bin/bash

mysql < /root/candh.sql
mysql < /root/createuser.sql

mkdir /nonexistent

a2enmod rewrite
a2enmod php8.3

echo 'ServerName localhost' >> /etc/apache2/apache2.conf
echo "SetHandler application/x-httpd-php" >> /etc/apache2/apache2.conf