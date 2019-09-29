<?php


namespace app\truck\controller;

use app\truck\common\HistoryWarning;
use app\truck\common\LayuiSupport;
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
        $rows = array();
        if (empty($car)){
            LayuiSupport::replyForTable($rows);
        }
        $rows = $historyWarning->warnings($car, $day);
            return LayuiSupport::replyForTable($rows);
    }

    public function warnings()
    {
        return $this->fetch();
    }

}