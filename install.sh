#!/bin/bash

rm -f /var/www/html/index.html

mysql < /root/candh.sql
mysql < /root/createuser.sql

cp /root/template-docker.dbconfig /var/www/html/config/dbconfig.cfg

a2enmod rewrite
a2enmod php8.3

echo 'ServerName localhost' >> /etc/apache2/apache2.conf
echo "SetHandler application/x-httpd-php" >> /etc/apache2/apache2.conf