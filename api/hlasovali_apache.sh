#!/bin/bash

# apache2 .conf files
MYSITE="dev.hlasovali.cz"

cd /tmp
wget "https://gist.githubusercontent.com/michalskop/9edee4757545c7d905c4/raw/3831a94d2f6fa33f2c4373be0f42a486a6aac6ca/https.example.com.conf" -O example.com.conf
grep -rl 'example.com' ./example.com.conf | xargs sed -i "s/example.com/$MYSITE/g"
sudo cp example.com.conf /etc/apache2/sites-available/$MYSITE.conf
sudo a2ensite $MYSITE
wget "https://gist.githubusercontent.com/michalskop/9edee4757545c7d905c4/raw/92a67b4302a1f46df7dbf4fe5a0b6783ca9db04a/api.example.com.conf" -O api.example.com.conf
grep -rl 'example.com' ./api.example.com.conf | xargs sed -i "s/example.com/$MYSITE/g"
sudo cp api.example.com.conf /etc/apache2/sites-available/api.$MYSITE.conf
sudo a2ensite api.$MYSITE

sudo service apache2 restart
