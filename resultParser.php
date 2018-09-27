<?php

$p = new ResultParser();
echo "Average % change (mssql):" . $p->mssqlAvg . "\n";
echo "Average % change (mysql):" . $p->mysqlAvg . "\n";

class ResultParser{
    const MSSQLINPUT = '/scan/watson/speedTest/redHat_suse_speeds_mssql.csv';
    const MYSQLINPUT = '/scan/watson/speedTest/redHat_suse_speeds_mysql.csv';

    public $mssqlAvg;
    public $mysqlAvg;

    public function __construct()
    {
        $files = array(self::MSSQLINPUT,self::MYSQLINPUT);
        foreach($files as $file){
            $this->_calculateAverage($file);
        }
    }
    protected function _calculateAverage($inputFile){
        $values = array();
        $csv = array_map('str_getcsv', file($inputFile));
        foreach($csv as $row){
            $values[] = $row[2];
        }
        $average = array_sum($values)/count($values);
        if($inputFile == self::MSSQLINPUT){
            $this->mssqlAvg = $average;
        }elseif($inputFile == self::MYSQLINPUT){
            $this->mysqlAvg = $average;
        }else{
            throw new Exception('Invalid Input File.');
        }
        return $this;
    }
}