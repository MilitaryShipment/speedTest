#!/bin/bash
HOST1=$1
HOST2=$2
MODE=$3
if [ "$HOST1" = "" ] || [ "$HOST2" = "" ]; then
	echo "USAGE: ./run.sh TARGET_HOST_OR_IP1 TARGET_HOST_OR_IP2 TARGET_DB_MODE"
	exit
fi
if [ "$MODE" = "0" ] ||  [ "$MODE" = "" ]; then
        OUTPUT="/scan/watson/speedTest/speed_test_mssql2.csv"
		INPUT='mssql.output.csv'
else
        OUTPUT='/scan/watson/speedTest/speed_test_mysql2.csv'
		INPUT='mysql.output.csv'
fi
./startServerCalls.sh $HOST1 $MODE
./startServerCalls.sh $HOST2 $MODE
php speedParser.php $MODE
rm -f $OUTPUT
cp $INPUT $OUTPUT

