<?php

while(true){
    $number = mt_rand(1,10);
    $option = $number % 2 == 0 ? 1 : 0;
    $cmd = './run.sh ' . $option;
    exec($cmd);
    sleep(120);
}
