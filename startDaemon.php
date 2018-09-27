<?php

$params = $argv;

if(!isset($params[1]) || !isset($params[2])){
    echo "USAGE:\n";
    echo "php startDaemon.php {HOSTNAME_OR_IP_1} {HOSTNAME_OR_IP_2}";
    exit;
}else{
    $host1 = $params[1];
    $host2 = $params[2];
}
while(true){
    $number = mt_rand(1,10);
    $option = $number % 2 == 0 ? 1 : 0;
    $cmd = './run.sh ' . $host1 . ' ' . $host2 . ' ' . $option;
    exec($cmd);
    sleep(120);
}
