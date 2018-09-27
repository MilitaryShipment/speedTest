#!/bin/bash
if [ "$1" = "0" ] ||  [ "$1" = "" ]; then
        OUTPUT='./redTimes.mssql.txt'
else
        OUTPUT='./redTimes.mysql.txt'
fi
for i in `seq 1 10`;
do
	(time curl "http://161.47.139.40/tmp/speedTest.php?mode=$1" 2>/dev/null) 1>/dev/null 2>>$OUTPUT
	sleep 2
done
