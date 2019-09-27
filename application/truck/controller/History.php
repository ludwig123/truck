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
        NetWorker::setCookie("JSESSIONID=28E118E5C5D3A0755487ABE4833C3A24; __guid=149418029.1707435643247522000.1566691178646.047; JSESSIONID=4932B2600699A5487403CB3D58A9198C; monitor_count=74; COOKIE_USERID_HD=a6d9405813f9d9116cb1a5225b9c9f86e49f7a0e21b52c4f8810abaa_1569569910530");
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