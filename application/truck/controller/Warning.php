<?php
namespace app\truck\controller;

use app\truck\model\WarningModel;
use app\truck\model\WarningUpdateLog;
use app\truck\common\NetWorker;
use app\truck\common\Tools;
use think\view\driver\Think;
use think\Controller;

/** 1、post_data 中的des和asc 排序是没有用的，永远都是时间utc值大的在列表的前面
 *  2、抓取间隔sleep 为1 秒，能勉强抓完一天的数据而不导致curl 出错
 *   2.1 目前发现以下错误： sleep 10 时候，curl_erro 7 ，无法建立连接；
 *                    sleep 1 时候， curl_erro 52 ,curl 没有获取到数据，实际上是没爬完的；
 *  3、alarmStartUtc 的值有问题，经常不是正确的utc 时间
 * 
 * 
 * 
 * 
 * @author ludwig
 * 2019年4月22日 下午7:22:11
 */
class Warning extends Controller
{

    public function index()
    {
        return $this->fetch();
        
    }
    
    
    
    public function speedCar(){
        $tools = new Tools();
        
        $model = new WarningModel();
        $start_time_utc = $model->getLastSpeedWarning();
        $end_time_utc = $tools->currentUtcMicro();
        
        //毫秒为单位  1000毫秒 60秒/分 ， 三分钟
        $min = 1000 * 60;
        
        $gap = $min * 3;
        
        while ($start_time_utc < $end_time_utc){
            $this->speedCarReal($start_time_utc, $start_time_utc + $gap);
            $start_time_utc += $gap;
            
        }
        echo "超速完毕";
        return ;
    }
    
    public function tiredCar(){
        $tools = new Tools();
        
        $model = new WarningModel();
        $start_time_utc = $model->getLastTiredWarning();
        $end_time_utc = $tools->currentUtcMicro();
        
        //毫秒为单位  1000毫秒 60秒/分 ， 三分钟
        $min = 1000 * 60;
        
        $gap = $min * 20;
        
        while ($start_time_utc < $end_time_utc){
            $this->tiredCarReal($start_time_utc, $start_time_utc + $gap);
            $start_time_utc += $gap;
            
        }
        echo '疲劳完毕';
        return ;
    }
    
    
    public function speedCarReal($start_time_utc, $end_time_utc){
        date_default_timezone_set('PRC');
        $tools = new Tools();
        $log = new WarningUpdateLog();
        $log->start_time = microtime(true);

        $speed = 103;
        
        // 每次登陆的cookie 都不同，需要手动设置
        $cookie = $this->getCookiesCache();
        
        $log->start = $tools->microUtcToDateTime($start_time_utc);
        $log->end = $tools->microUtcToDateTime($end_time_utc);
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
        $log->start_time = $tools->utcToDateTime(microtime(true));
        echo '本次测速抓取耗时'.($log->cost_time).'秒。';
        $log->add();
    }
    
    public function tiredCarReal($start_time_utc, $end_time_utc){
        date_default_timezone_set('PRC');
        $tools = new Tools();
        $log = new WarningUpdateLog();
        $log->start_time = microtime(true);
        
        
       
        
        // 每次登陆的cookie 都不同，需要手动设置
        $cookie = $this->getCookiesCache();
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
        echo '本次抓取疲劳数据共：'.($log->tired_records).'条\n。';
        
        $log->cost_time = microtime(true) - $log->start_time;
        $log->start_time = $tools->utcToDateTime(microtime(true));
        echo '本次疲劳抓取耗时'.($log->cost_time).'秒。';
        $log->add();
    }

    public function car()
    {
        $this->speedCar();
        $this->tiredCar();
    }

    // 疲劳预警
    /**
     * 疲劳驾驶总是将当天的疲劳总数显示出来，但还是可以指定查询的时间段
     *
     * @param integer $start_time_utc
     * @param integer $end_time_utc
     * @param integer $cookie
     */
    public function saveTiredWarning($start_time_utc, $end_time_utc, $cookie, $page = 1)
    {
        $data = $this->getDataTired($start_time_utc, $end_time_utc, $page);
        $dataLen = strlen($data);
        $header = $this->getHeader($cookie, $dataLen);
        $url = $this->getUrlAlarmCar();
        
        $worker = new NetWorker();
        $resultStr = $worker->sentPost($header, $data, $url);
        
        $savedCount = $this->saveRows($resultStr);
        
        if (is_numeric($savedCount)) {
            
            if ($savedCount > 0) {
                echo "保存" . $savedCount . "条疲劳数据\n,进入下一页查询\n";
            } else {
                echo "保存" . $savedCount . "条疲劳数据\n";
            }
            return $savedCount;
        } else {
            echo $savedCount;
            return 0;
        }
    }

    public function saveSpeedWarning($start_time_utc, $end_time_utc, $cookie, $speed, $page = 1)
    {
        $data = $this->getDataSpeedChina($start_time_utc, $end_time_utc, $page, $speed);
        $dataLen = strlen($data);
        $header = $this->getHeader($cookie, $dataLen);
        $url = $this->getUrlAlarmCar();
        
        
        $worker = new NetWorker();
        $resultStr = $worker->sentPost($header, $data, $url);
        
        $savedCount = $this->saveRows($resultStr);
        
        if (is_numeric($savedCount)) {
            
            if ($savedCount > 0) {
                echo "保存" . $savedCount . "条超速数据\n,进入下一页查询\n";
            } else {
                // 数据重复导致没有保存
                echo "保存" . $savedCount . "条超速数据\n";
            }
            return $savedCount;
        } else {
            echo $savedCount;
            return 0;
        }
    }
    
    public function getAddrList($rows){
        $data = $this->getDataAddr($rows);
        $dataLen = strlen($data);
        $cookie = $this->getCookiesCache();
        $header = $this->getHeader($cookie, $dataLen);
        $url = $this->getUrlfindAddrByLatitude();
        
        $worker = new NetWorker();
        $result = $worker->sentPost($header, $data, $url);
        return $result;
    }
        
    public function getDataAddr($rows){
        $data = 'latLon=';
        foreach ($rows as $k => $row){
            $data .= $row['alarmId'].'%2C'.($row['maplon']/600000).'%2C'.($row['maplat']/600000).'%3B';
        }
        
        return $data;
    }

    public function saveRows($resultStr)
    {
        if ($resultStr == ''){
            echo 'sessinId 过期！';
            exit();
            
        }
        
        $result = json_decode($resultStr, true);
        
        $total = $result['Total'];
        $rows = $result['Rows'];
        
        // 服务器没有返回数据
        if (empty($rows))
            return '当前时间段没有数据';
        
        //通过经纬度查询具体的地址
        $addrListStr = $this->getAddrList($rows);
        $addrList = json_decode($addrListStr, true);
        
        
        $rows = $this->addAddrToAlarmList($addrList, $rows);
        
        $rows = $this->reduceRows($rows, '衡阳市');
        
        $model = new WarningModel();
        $savedCount = $model->add($rows);
        sleep(0.1);
        return $savedCount;
    }
    
    private function addAddrToAlarmList($addrList, $rows){
        $records = array();
        foreach ($rows as $k => $row){
            $key = array_search($row['alarmId'], array_column($addrList, 'key'));
           $addr = $addrList[$key]['values'];
            $row['alarmAddr']  = $addr;
            $records[]=$row;
        }
        return $records;
    }


    public function getCookie()
    {
        $cookie = 'JSESSIONID=EF6B467D56FF3A90EACEB79D6A4D26B5; __guid=149418029.2417622607162884600.1555312459470.336; JSESSIONID=B97EA0F689CD03E4AF7BD28050FE76DE; COOKIE_USERID_HD=a6d341e72365c1590c9c595aad739bffe0db0d293f54281a6629fd1d_1557665014295; monitor_count=5';
        return $cookie;
    }

    public function getUrlAlarmCar()
    {
        return "https://jg.gghypt.net/hyjg/statistics/statisticsAction!findVehicleAlarmByPage.action";
    }
    
    /** 通过经纬度列表查询文字地址<br>
     * 如： ‘湖南省衡阳市衡南县黄土坳，向西南方向，230米’
     * @return string
     */
    public function getUrlfindAddrByLatitude()
    {
        return "https://jg.gghypt.net/hyjg/monitor/monitorAction!findAddressList.action";
    }

    public function getStartTime()
    {
        //return 1555689600000;
        return 1557158400000;
    }

    /**
     * 分钟为单位，默认1分钟
     */
    public function getStep($step = 1)
    {
        return $step * 1000 * 60; // 毫秒
    }

    /**
     *
     * @param string $cookie
     * @param integer $dataLen
     * @return string[]
     */
    public function getHeader($cookie, $dataLen)
    {
        return array(
            'Accept:application/json, text/javascript, */*; q=0.01',
            'Accept-Encoding:gzip, deflate, br',
            'Accept-Language:zh-CN,zh;q=0.9',
            'Connection:keep-alive',
            'Content-Length:' . $dataLen,
            'Content-Type:application/x-www-form-urlencoded',
            'Cookie:JSESSIONID=' . $cookie,
            'Host:jg.gghypt.net',
            'Origin:https://jg.gghypt.net',
            'Referer:https://jg.gghypt.net/hyjg/statisticsAction!statisticsVehicleAlarm.action',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
            'X-Requested-With:XMLHttpRequest'
        );
    }

    /**
     * 默认获取101km/h
     *
     * @param integer $startTime
     * @param integer $endTime
     * @param number $speed
     * @return string
     */
    public function getDataSpeed($start_time_utc, $end_time_utc, $page = 1, $speed = 101)
    {
        $rows = 40;
        return 'undefined=undefined&requestParam.equal.alarmCode=1&requestParam.equal.gpsSpeed=' . $speed . '&undefined=undefined&requestParam.equal.startTimeUtc=' . $start_time_utc . '&requestParam.equal.endTimeUtc=' . $end_time_utc . '&requestParam.equal.areaCode=430000&undefined=undefined&undefined=undefined&requestParam.page=' . $page . '&requestParam.rows='.$rows.'&sortname=alarmStartUtc&sortorder=des';
    }
    
    public function getDataSpeedChina($start_time_utc, $end_time_utc, $page = 1, $speed = 101)
    {
        $rows = 40;
        return 'undefined=undefined&requestParam.equal.alarmCode=1&requestParam.equal.gpsSpeed=' . $speed . '&undefined=undefined&requestParam.equal.startTimeUtc=' . $start_time_utc . '&requestParam.equal.endTimeUtc=' . $end_time_utc . '&undefined=undefined&undefined=undefined&undefined=undefined&requestParam.page=' . $page . '&requestParam.rows='.$rows.'&sortname=alarmStartUtc&sortorder=des';
    }

    public function getDataTired($start_time_utc, $end_time_utc, $page = 1)
    {
        $rows = 40;
        return 'undefined=undefined&requestParam.equal.alarmCode=2&undefined=undefined&undefined=undefined&requestParam.equal.startTimeUtc=' . $start_time_utc . '&requestParam.equal.endTimeUtc=' . $end_time_utc . '&requestParam.equal.areaCode=430000&undefined=undefined&undefined=undefined&requestParam.page='.$page.'&requestParam.rows='.$rows.'&sortname=alarmStartUtc&sortorder=des';
    }
    
    public function getLastWarning(){
        $model = new WarningModel();
        $utc =  $model->getLastWarning();
        return $utc;
    }
    
    public function getEndTime($startTime, $range){
        
    }
    
    public function getCookiesCache(){
        $cookie = cache('truckCookie');
        if ($cookie == null){
            return 'empty_cookie';
        }
        
        else 
            return $cookie;
    }
    
    
    public function reduceRows($rows, $destination){
        $result = array();
        foreach ($rows as $k => $v){
            if (preg_match('/'.$destination.'/u', $v['alarmAddr'])){
                $result[] = $v;
            }
        }
        return $result;
    }
    
    
}