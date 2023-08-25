#!/bin/bash

# imports the DB and creates the users
# then runs services

# start mysql service
service mysql start

# import the DB and create the users
mysql < /root/candh.sql
mysql < /root/createuser.sql

# start apache2
service apache2 start

exec /bin/bash