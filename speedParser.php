<?php

$params = $argv;

if(!isset($params[1]) || $params[1] == 0){
    $outfile = './mssql.output.csv';
    $mode = 0;
}else{
    $outfile = './mysql.output.csv';
    $mode = 1;
}
$file = fopen($outfile,"w");
try{
    $p = new Parser($mode);
    fputcsv($file,$p->headers);
    foreach($p->results as $result){
        fputcsv($file,$result);
    }
}catch(Exception $e){
    echo $e->getMessage() . "\n";
}
fclose($file);

class Parser{

    const REDTIMES_MYSQL = './redTimes.mysql.txt';
    const REDTIMES_MSSQL = './redTimes.mssql.txt';
    const SUSETIMES_MYSQL = './suseTimes.mysql.txt';
    const SUSETIMES_MSSQL = './suseTimes.mssql.txt';
    //const TIMEPAT = '/real\s\s\s\s([0-9])m([0-9])\.([0-9]{3})/';
    const TIMEPAT = '/real[ \t]+([0-9])m([0-9])\.([0-9]{3})/';

    protected $redTimes;
    protected $suseTimes;
    public $headers = array('SUSE_TIME','RED_TIME','PERCENT_DIFF');
    public $results = array();

    public function __construct($mode = 0)
    {
        if(!$mode){
            $files = array(self::SUSETIMES_MSSQL,self::REDTIMES_MSSQL);
        }else{
            $files = array(self::SUSETIMES_MYSQL,self::REDTIMES_MYSQL);
        }
        foreach($files as $file){
            $this->_loadTimes($file);
        }
        $this->_calculate();

    }
    protected function _loadTimes($fileName){
        $lines = file($fileName);
        $times = array();
        foreach($lines as $line){
            if($timeStr = $this->_interpretLine($line)){
                $times[] = $timeStr;
            }
        }
        if($fileName == self::REDTIMES_MYSQL || $fileName == self::REDTIMES_MSSQL){
            $this->redTimes = $times;
        }elseif($fileName == self::SUSETIMES_MYSQL || $fileName == self::SUSETIMES_MSSQL){
            $this->suseTimes = $times;
        }else{
            throw new Exception('Invalid Option');
        }
        return $this;
    }
    protected function _interpretLine($line){
        if(preg_match(self::TIMEPAT,$line,$matches)){
            $timeStr = $matches[2] . '.' . $matches[3];
            return $timeStr;
        }
        return false;
    }
    protected function _calculate(){
        if(count($this->redTimes) != count($this->suseTimes)){
            throw new Exception('Time Arrays Do not match.');
        }
        for($i = 0; $i < count($this->redTimes); $i++){
            $percent = round((($this->suseTimes[$i] / $this->redTimes[$i]) * 100) - 100,2);
            $this->results[] = array($this->suseTimes[$i],$this->redTimes[$i],$percent);
        }
        return $this;
    }
}
