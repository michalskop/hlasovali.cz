#!/bin/bash

# installing for example.com and www.example.com
# https://www.digitalocean.com/community/tutorials/how-to-secure-apache-with-let-s-encrypt-on-ubuntu-14-04


MYSITE="dev.hlasovali.cz"

sudo apt-get update
sudo apt-get install git

sudo git clone https://github.com/letsencrypt/letsencrypt /opt/letsencrypt
cd /opt/letsencrypt

./letsencrypt-auto --apache -d $MYSITE -d www.$MYSITE

# http://stackoverflow.com/a/878647/1666623
cd /tmp
sudo crontab -l > mycron
echo "30 1 * * 1 /opt/letsencrypt/letsencrypt-auto renew >> /var/log/le-renew.log" >> mycron
sudo crontab mycron
rm mycron
