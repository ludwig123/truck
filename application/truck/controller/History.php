<?php


namespace app\truck\controller;

use app\truck\common\CarRepository;
use app\truck\common\HistoryWarning;
use app\truck\common\LayuiSupport;
use app\truck\common\NetWorker;
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



    public function warningsBoth($car='',$day = '7', $page = 1, $limit = 10)
    {
        $historyWarning = new HistoryWarning();
        if (empty($car)){
            return [];
        }
        $rows = $historyWarning->warnings($car, $day);
            return LayuiSupport::replyForTable($rows);
    }

    public function warnings()
    {
        return $this->fetch();
    }

}