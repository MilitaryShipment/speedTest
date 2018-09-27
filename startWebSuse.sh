#!/bin/bash
if [ "$1" = "0" ] ||  [ "$1" = "" ]; then
        OUTPUT='./suseTimes.mssql.txt'
else
        OUTPUT='./suseTimes.mysql.txt'
fi
for i in `seq 1 10`;
do
	(time curl "http://172.24.16.20/tmp/speedTest.php?mode=$1" 2>/dev/null) 1>/dev/null 2>>$OUTPUT
	sleep 2
done
