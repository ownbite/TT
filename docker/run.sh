#!/bin/bash

# Set constants
BASE_PATH='/var/www/html'

# Fix config file for cache plugin
touch $BASE_PATH/nginx.conf
chown www-data:www-data $BASE_PATH/nginx.conf
chmod 755 $BASE_PATH/nginx.conf

# Move the new config file
mv /tmp/wp-config.php $BASE_PATH/wp-config.php
chown www-data:www-data $BASE_PATH/wp-config.php

# Chowm cache folder
chown www-data:www-data $BASE_PATH/wp-content/cache -R

# Restart nginx
service nginx restart

