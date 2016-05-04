#!/bin/bash

MYNAME="michal"

# user
# passwd    # password for root
echo "password for "$MYNAME":"
useradd $MYNAME
passwd $MYNAME
sudo adduser $MYNAME sudo
su $MYNAME
sudo mkdir /home/$MYNAME

# update
sudo apt-get -f autoremove
sudo apt-get update
sudo apt-get upgrade
sudo apt-get dist-upgrade

# locales
sudo locale-gen cs_CZ
sudo locale-gen cs_CZ.UTF-8
sudo update-locale

# Apache2
sudo apt-get install apache2
sudo a2enmod rewrite
sudo service apache2 restart

# php7
sudo apt-get update --fix-missing
sudo apt-get install php php-fpm php-mcrypt
