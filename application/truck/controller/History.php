<?php


namespace app\truck\controller;

use app\truck\common\LayuiSupport;
use app\truck\common\Track;
use think\Controller;

class History extends Controller
{
    public function overSpeed()
    {
       return $this->fetch();
        //var_dump($speedRecords);
        if (empty($speedRecords))
        {
            echo 'not over speed！';
        }


    }

    public function speedHistory($car='', $time = '24', $page = 1, $limit = 10)
    {
        $car = '湘D9999';
        $track =new Track();
        $speedRecords  = $track->findTrack($car, $time);
        if (!empty($speedRecords)){
            for ($i = 0; $i < count($speedRecords); $i++){
                $speedRecords[$i]['carNum'] = $car;
            }
        }
        return LayuiSupport::replyForTable($speedRecords,$page, $limit);
    }

    public function warnings($car='', $page = 1, $limit = 10)
    {


    }

}