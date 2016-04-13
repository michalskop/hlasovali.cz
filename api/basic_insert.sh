#!/bin/bash

MYEMAIL="michal.skop@kohovolit.eu"
MYPASS="example"
MYNAME="Michal Škop"

psql -d hlasovali -c "INSERT INTO basic_auth.users(email,pass,role,verified,name) VALUES ('$MYEMAIL','$MYPASS','admin',true,'$MYNAME')"

#psql -c "ALTER USER postgres WITH PASSWORD '$MYPGSQLPASS'"
