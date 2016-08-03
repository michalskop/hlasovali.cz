#!/bin/bash

# installing for example.com and www.example.com
#
#https://certbot.eff.org/#ubuntutrusty-apache
# https://www.digitalocean.com/community/tutorials/how-to-secure-apache-with-let-s-encrypt-on-ubuntu-14-04


MYSITE="dev.hlasovali.cz"

# sudo apt-get update
# sudo apt-get install git

# sudo git clone https://github.com/letsencrypt/letsencrypt /opt/letsencrypt
# cd /opt/letsencrypt

sudo -i
cd /opt/letsencrypt/

wget https://dl.eff.org/certbot-auto
chmod a+x certbot-auto
exit

# ./letsencrypt-auto --apache -d $MYSITE -d www.$MYSITE
cd /opt/letsencrypt/
./certbot-auto

# http://stackoverflow.com/a/878647/1666623
cd /tmp
#sudo crontab -l > mycron
crontab -l > mycron
# echo "30 1 * * * /opt/letsencrypt/letsencrypt-auto renew >> /var/log/le-renew.log" >> mycron
echo "3 3,15 * * * /opt/letsencrypt/certbot-auto renew --quiet --no-self-upgrade" >> mycron
# sudo crontab mycron
crontab mycron
rm mycron
