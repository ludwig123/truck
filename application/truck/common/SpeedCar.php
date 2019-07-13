<?php


namespace app\truck\common;


use app\truck\model\WarningModel;
use app\truck\model\WarningUpdateLog;

class SpeedCar
{
    public function speedCar(){

        $start_time_utc = $this->getLastSpeedWarning();
        $end_time_utc = TimeTranslator::currentUtcMicro();

        $step = MICRO_SECONDS_PER_MINUTE * 3;

        while ($start_time_utc < $end_time_utc){
            $this->speedCarReal($start_time_utc, $start_time_utc + $step);
            $start_time_utc += $step;

        }
        echo "超速完毕";
        return ;
    }

    public function speedCarReal($start_time_utc, $end_time_utc){
        date_default_timezone_set('PRC');
        $log = new WarningUpdateLog();
        $log->start_time = microtime(true);

        $speed = 103;

        // 每次登陆的cookie 都不同，需要手动设置
        $cookie = NetWorker::getCookiesCache();

        $log->start = TimeTranslator::microUtcToDateTime($start_time_utc);
        $log->end = TimeTranslator::microUtcToDateTime($end_time_utc);
        $log->speed_records = 0;


        //阻塞式请求
        $speedPage = 0;
        do
        {
            $speedPage++;
            $savedCount = $this->saveSpeedWarning($start_time_utc, $end_time_utc, $cookie, $speed, $speedPage);
            $log->speed_records += $savedCount;
            ob_flush();
            flush();
        }while (is_numeric($savedCount) && $savedCount > 0);
        echo '本次抓取超速数据共：'.($log->speed_records).'条\n。';

        $log->cost_time = microtime(true) - $log->start_time;
        $log->start_time = TimeTranslator::utcToDateTime(microtime(true));
        echo '本次测速抓取耗时'.($log->cost_time).'秒。';
        $log->add();
    }

    public function saveSpeedWarning($start_time_utc, $end_time_utc, $cookie, $speed, $page = 1)
    {
        $data = $this->getDataSpeedChina($start_time_utc, $end_time_utc, $page, $speed);
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
     * 获取湖南省的超速车辆
     * 默认获取101km/h
     */
    public function getDataSpeed($start_time_utc, $end_time_utc, $page = 1, $speed = 101)
    {
        $rows = 40;
        return 'undefined=undefined&requestParam.equal.alarmCode=1&requestParam.equal.gpsSpeed=' . $speed . '&undefined=undefined&requestParam.equal.startTimeUtc=' . $start_time_utc . '&requestParam.equal.endTimeUtc=' . $end_time_utc . '&requestParam.equal.areaCode=430000&undefined=undefined&undefined=undefined&requestParam.page=' . $page . '&requestParam.rows='.$rows.'&sortname=alarmStartUtc&sortorder=des';
    }

    public function getLastSpeedWarning()
    {
        $model = new WarningModel();
        $lastSpeedWarning = $model->getLastSpeedWarning();
        return $lastSpeedWarning;
    }

    /**
     * 获取全国的超速车辆
     * 默认获取101km/h
    */
    public function getDataSpeedChina($start_time_utc, $end_time_utc, $page = 1, $speed = 101)
    {
        $rows = 40;
        return 'undefined=undefined&requestParam.equal.alarmCode=1&requestParam.equal.gpsSpeed=' . $speed . '&undefined=undefined&requestParam.equal.startTimeUtc=' . $start_time_utc . '&requestParam.equal.endTimeUtc=' . $end_time_utc . '&undefined=undefined&undefined=undefined&undefined=undefined&requestParam.page=' . $page . '&requestParam.rows='.$rows.'&sortname=alarmStartUtc&sortorder=des';
    }
}