<?php


namespace app\truck\common;


use app\truck\model\WarningModel;
use app\truck\model\WarningUpdateLog;

class TiredCar
{
    private $isChina;
    function __construct($isChina)
    {
        $this->isChina = $isChina;
    }

    public function tiredCar(){
        $start_time_utc = $this->getLastTiredWarning();
        $end_time_utc = TimeTranslator::currentUtcMicro();

        $gap = MICRO_SECONDS_PER_MINUTE * 3;

        while ($start_time_utc < $end_time_utc){
            $this->tiredCarReal($start_time_utc, $start_time_utc + $gap);
            $start_time_utc += $gap;

        }
        echo '疲劳完毕';
        return ;
    }

   public function tiredCarReal($start_time_utc, $end_time_utc){
        date_default_timezone_set('PRC');
        $tools = new TimeTranslator();
        $log = new WarningUpdateLog();
        $log->start_time = microtime(true);




        // 每次登陆的cookie 都不同，需要手动设置
        $cookie = NetWorker::getCookiesCache();
        $log->start = $tools->microUtcToDateTime($start_time_utc);
        $log->end = $tools->microUtcToDateTime($end_time_utc);
        $log->speed_records = 0;

        $log->tired_records = 0;
        $tiredPage = 0;
        do
        {
            $tiredPage++;
            $savedCountTired = $this->saveTiredWarning($start_time_utc, $end_time_utc, $cookie, $tiredPage);
            $log->tired_records += $savedCountTired;
            ob_flush();
            flush();
        }while (is_numeric($savedCountTired) && $savedCountTired > 0);
        echo '从'.$log->start.'--'.$log->end.'抓取疲劳数据共:'.($log->tired_records).'条,';

        $log->cost_time = microtime(true) - $log->start_time;
        $log->start_time = $tools->utcToDateTime(microtime(true));
        echo '耗时'.round($log->cost_time, 2).'秒。<br>';
        $log->add();
    }

    /**
     * 疲劳驾驶总是将当天的疲劳总数显示出来，但还是可以指定查询的时间段
     *
     */
    public function saveTiredWarning($start_time_utc, $end_time_utc, $cookie, $page = 1)
    {
        $data = $this->getDataTired($start_time_utc, $end_time_utc, $page);
        $header = NetWorker::getHeader($cookie, $data);
        $url = URLRepository::vehicleAlarmUrl();


        $resultStr = NetWorker::getPostResult($header, $data, $url);
        if ($resultStr == ''){
            echo 'sessinId 过期！';
            exit();
        }
        $savedCount = CarRepository::saveRows($resultStr);

        return $savedCount;
    }


    /**
     * @return string
     */
    public function getLastTiredWarning()
    {
        $model = new WarningModel();
        $start_time_utc = $model->getLastTiredWarning();
        return $start_time_utc;
    }

    public function getDataTired($start_time_utc, $end_time_utc, $page = 1)
    {
        if ($this->isChina){
            return $this->getDataTiredChina($start_time_utc, $end_time_utc, $page = 1);
        }
        else
            return $this->getDataTiredHunan($start_time_utc, $end_time_utc, $page = 1);
          }

    public function getDataTiredChina($start_time_utc, $end_time_utc, $page = 1)
    {
        $rows = 40;
        return 'undefined=undefined&requestParam.equal.alarmCode=2&undefined=undefined&undefined=undefined&requestParam.equal.startTimeUtc=' . $start_time_utc . '&requestParam.equal.endTimeUtc=' . $end_time_utc . '&undefined=undefined&undefined=undefined&undefined=undefined&requestParam.page='.$page.'&requestParam.rows='.$rows.'&sortname=alarmStartUtc&sortorder=des';
    }

    public function getDataTiredHunan($start_time_utc, $end_time_utc, $page = 1)
    {
        $rows = 40;
        return 'undefined=undefined&requestParam.equal.alarmCode=2&undefined=undefined&undefined=undefined&requestParam.equal.startTimeUtc=' . $start_time_utc . '&requestParam.equal.endTimeUtc=' . $end_time_utc . '&requestParam.equal.areaCode=430000&undefined=undefined&undefined=undefined&requestParam.page='.$page.'&requestParam.rows='.$rows.'&sortname=alarmStartUtc&sortorder=des';
    }

}