#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.


# Installs Imagemagick
sudo apt-get update && sudo apt-get install -y imagemagick php-imagick && sudo service php7.*-fpm restart
