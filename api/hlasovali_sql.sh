#!/bin/bash

APIPATH="/home/projects/hlasovali.cz/api/"

# sudo su postgres

createdb hlasovali -O postgres -E UTF-8 -D pg_default --lc-collate cs_CZ.UTF-8 --lc-ctype cs_CZ.UTF.8 -T template0

psql -f "$APIPATH"basic_auth_setup.sql -d hlasovali
psql -f "$APIPATH"hlasovali.sql -d hlasovali
