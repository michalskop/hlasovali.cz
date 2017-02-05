#!/bin/bash

# note: if necessary run as (sudo) /bin/bash ./postgrest.sh

PGSQLPASS="example"
PRESTSECRET="example_secret"
DB="hlasovali"

# Postgres 9.5
# https://www.howtoforge.com/tutorial/how-to-install-postgresql-95-on-ubuntu-12_04-15_10/
sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" >> /etc/apt/sources.list.d/pgdg.list'
wget -q https://www.postgresql.org/media/keys/ACCC4CF8.asc -O - | sudo apt-key add -

sudo apt-get update
sudo apt-get install postgresql postgresql-contrib

sudo passwd postgres

# PostgREST
# postgrest v0.3.2.0
# https://github.com/begriffs/postgrest/releases/
cd /tmp
wget https://github.com/begriffs/postgrest/releases/download/v0.3.2.0/postgrest-0.3.2.0-ubuntu.tar.xz
sudo tar xf postgrest-0.3.2.0-ubuntu.tar.xz
sudo cp postgrest /opt/postgrest

sudo apt-get install libgmp-dev

wget "https://gist.githubusercontent.com/michalskop/9edee4757545c7d905c4/raw/bc54f641bf98f164d283153236b7568092d5d931/postgrest.conf" -O postgrest.conf
grep -rl 'example_password' ./postgrest.conf | xargs sed -i "s/example_password/$PGSQLPASS/g"
grep -rl 'example_secret' ./postgrest.conf | xargs sed -i "s/example_secret/$PRESTSECRET/g"
grep -rl 'activities' ./postgrest.conf | xargs sed -i "s/activities/$DB/g"
sudo cp postgrest.conf /etc/init/postgrest.conf
sudo service postgrest start
