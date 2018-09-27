#!/bin/bash
if [ "$1" = "0" ] ||  [ "$1" = "" ]; then
        OUTPUT='/scan/watson/speedTest/redHat_suse_speeds_mssql.csv'
		INPUT='mssql.output.csv'
else
        OUTPUT='/scan/watson/speedTest/redHat_suse_speeds_mysql.csv'
		INPUT='mysql.output.csv'
fi
./startRedHat.sh $1
./startWebSuse.sh $1
php speedParser.php $1
rm -f $OUTPUT
cp $INPUT $OUTPUT

