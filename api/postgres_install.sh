#!/bin/bash

# note: if necessary run as (sudo) /bin/bash ./postgrest.sh

PGSQLPASS="example"
PRESTSECRET="example_secret"

# Postgres 9.5
# https://www.howtoforge.com/tutorial/how-to-install-postgresql-95-on-ubuntu-12_04-15_10/
sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" >> /etc/apt/sources.list.d/pgdg.list'
wget -q https://www.postgresql.org/media/keys/ACCC4CF8.asc -O - | sudo apt-key add -

sudo apt-get update
sudo apt-get install postgresql postgresql-contrib

sudo passwd postgres


# MYPGSQLPASS='example'
#
# MYEMAIL="michal.skop@example.com"
# MYPASS="example"



# psql -f /home/projects/cz.parldata.net/api/setup.sql -d activities
#
# psql -d activities -c "INSERT INTO basic_auth.users VALUES ('$MYEMAIL','$MYPASS','author',true)"
#
# psql -c "ALTER USER postgres WITH PASSWORD '$MYPGSQLPASS'"
