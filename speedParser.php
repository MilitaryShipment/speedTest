<?php

$params = $argv;

if(!isset($params[1]) || $params[1] == 0){
    $outfile = './mssql.output.csv';
}else{
    $outfile = './mysql.output.csv';
}
$file = fopen($outfile,"w");
try{
    $p = new Parser();
    fputcsv($file,$p->headers);
    foreach($p->results as $result){
        fputcsv($file,$result);
    }
}catch(Exception $e){
    echo $e->getMessage() . "\n";
}
fclose($file);

class Parser{

    const TIMEPAT = '/real[ \t]+([0-9])m([0-9])\.([0-9]{3})/';
    const RESULTPAT = '/sql.txt/';
    const HOSTPAT = '/(.*).[a-z]{2}sql.txt/';
    const RESULTDIR = './';
    const FINALHEADER = 'PERCENT_DIFF';

    protected $serverALabel;
    protected $serverBLabel;
    protected $serverATimes;
    protected $serverBTimes;
    protected $resultFiles = array();
    protected $hosts = array();
    public $headers = array();
    public $results = array();

    public function __construct()
    {
        $this->_scanFiles()->_parseHosts()->_buildHeaders();
        foreach($this->resultFiles as $file){
            $this->_loadTimes($file);
        }
        $this->_calculate();

    }
    protected function _scanFiles(){
        $results = scandir(self::RESULTDIR);
        foreach($results as $res){
            if(preg_match(self::RESULTPAT,$res)){
                $this->resultFiles[] = $res;
            }
        }
        $this->serverALabel = $this->resultFiles[0];
        $this->serverBLabel = $this->resultFiles[1];
        return $this;
    }
    protected function _parseHosts(){
        foreach($this->resultFiles as $file){
            if(preg_match(self::HOSTPAT,$file,$matches)){
                $this->hosts[] = $matches[1];
            }
        }
        return $this;
    }
    protected function _buildHeaders(){
        foreach($this->hosts as $host){
            $this->headers[] = $host;
        }
        $this->headers[] = self::FINALHEADER;
        return $this;
    }
    protected function _loadTimes($fileName){
        $lines = file($fileName);
        $times = array();
        foreach($lines as $line){
            if($timeStr = $this->_interpretLine($line)){
                $times[] = $timeStr;
            }
        }
        if($fileName == $this->serverALabel){
            $this->serverATimes = $times;
        }elseif($fileName == $this->serverBLabel){
            $this->serverBTimes = $times;
        }else{
            echo $fileName . "\n";
            print_r($this->resultFiles);
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
        if(count($this->serverATimes) != count($this->serverBTimes)){
            throw new Exception('Time Arrays Do not match.');
        }
        for($i = 0; $i < count($this->serverBTimes); $i++){
            $percent = round((($this->serverATimes[$i] / $this->serverBTimes[$i]) * 100) - 100,2);
            $this->results[] = array($this->serverATimes[$i],$this->serverBTimes[$i],$percent);
        }
        return $this;
    }
}
