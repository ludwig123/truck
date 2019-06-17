<?php
namespace app\truck\model;

use think\Db;
use think\Model;


class WarningUpdateLog
{

    public $from_time, $to_time, $speed_records, $tired_records, $start_time, $cost_time, $start, $end;

    public function add()
    {
        $record = $this->prepare();
        $sum = 0;
        try {
            // 不需要前缀wp_
            $sum = Db::name('warning_update_log')->insert($record);
        } catch (\Exception $e) {
            // var_dump($e);
        }
        return $sum;
    }

    public function prepare()
    {
        $record = array();
        
        //抓取的记录的范围
        
        $record['start'] = $this->start;
        $record['end'] = $this->end;
        
        
        $record['speed_records'] = $this->speed_records;
        $record['tired_records'] = $this->tired_records;
        
        //抓取运行的时间
        $record['start_time'] = $this->start_time;
        $record['cost_time'] = $this->cost_time;
        
        return $record;
    }
    
    public function getLatestLog(){
        $log = Db::name('warning_update_log')->max('to_time');
        return $log;
    }
}