#!/bin/bash
HOST=$1
if [ "$2" = "0" ] ||  [ "$2" = "" ]; then
        OUTPUT="./$HOST.times.mssql.txt"
else
        OUTPUT="./$HOST.times.mysql.txt"
fi
for i in `seq 1 10`;
do
	(time curl "http://$HOST/tmp/speedTest.php?mode=$1" 2>/dev/null) 1>/dev/null 2>>$OUTPUT
	sleep 2
done
