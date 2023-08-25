# Use Ubuntu as the base image
FROM ubuntu:latest

# Set environment variables for MySQL to prevent prompts during installation
ENV DEBIAN_FRONTEND=noninteractive
ENV MYSQL_ROOT_PASSWORD=spacemanspiff
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_PID_FILE /var/run/apache2/apache2.pid
ENV APACHE_LOG_DIR /var/log/apache2

# Update package lists and install software-properties-common to add repositories
RUN apt-get update && \
    apt-get install -y software-properties-common less vim ack && \
    apt-get clean

# Add PHP 8.0 repository
RUN add-apt-repository ppa:ondrej/php

# Update package lists again and install Apache, MySQL Server, PHP 8.0, and necessary extensions
RUN apt-get update && \
    apt-get install -y apache2 mysql-server php8.3 php8.3-mysql && \
    apt-get clean

# Copy htdocs to Apache root
# COPY htdocs /var/www/html
## use -v to mount the repo's html directory to the container's /var/www/html
# docker run -d -p 80:80 -v /path/to/repo/html:/var/www/html --name candh candh

# Enable Apache modules
RUN a2enmod rewrite

# copy the database to the container
COPY candh.sql /root

# copy the createuser.sql file to the container
COPY createuser.sql /root

# Copy and run install script
COPY install.sh /root
RUN chmod +x /root/install.sh
RUN /root/install.sh

# Copy run script
COPY run.sh /root
RUN chmod +x /root/run.sh

# Start Apache and MySQL services
# CMD /root/run.sh && tail -f /dev/null

ENTRYPOINT ["/bin/bash", "/root/run.sh"]