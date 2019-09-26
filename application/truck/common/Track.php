<?php


namespace app\truck\common;


/**
 * Class Track 查询车辆轨迹
 * @package app\truck\common
 */
class Track
{
    public function findTrack($car, $gapHour)
    {
        $carNumToVid = new  CarNumToVid();
        $vid = $carNumToVid->getVid($car);
        //！！！！！注意这里的时间要添加 ：00 用于毫秒级的时间转换！！
        $startTime = $this->getStartTime($gapHour);
        $endTime = $this->getEndTime();
        $cookie = NetWorker::getCookiesCache();
//        $vid = '2698081623798745914';
        $queryId = $this->getQueryId($vid);
        $post_data['requestParam.equal.id'] = $vid;
        $post_data['requestParam.equal.startTime'] = $startTime;
        $post_data['requestParam.equal.endTime']= $endTime;
        $post_data['requestParam.equal.orgCode'] = '430000';
        $post_data['requestParam.equal.queryId'] = $queryId;
        $post_data['requestParam.equal.init'] = 1;
        $post_data['requestParam.equal.trailDataKey'] = $this->uuid_v4();
        $post_data['requestParam.equal.searchType'] = 1;




        //$cookie = NetWorker::getCookiesCache();
        $worker = new NetWorker();
        $header = $this->getHeader($cookie);
        $data=$this->getPostData($post_data);
        //$data = 'requestParam.equal.id=6239080096271702727&requestParam.equal.startTime=2019-09-15+12%3A38%3A33%3A00&requestParam.equal.endTime=2019-09-16+12%3A38%3A33%3A00&requestParam.equal.orgCode=430000&requestParam.equal.queryId=6239080096271702727_0_1568608727202&requestParam.equal.init=1&requestParam.equal.trailDataKey=1570A981-AF68-4607-ACBF-AA1B141AFC38&requestParam.equal.searchType=1';
        $url = URLRepository::findTrackUrl();
        $response = $worker->sentPost($header, $data, $url);
        $records = json_decode($response, true);
        $rows = $records['Rows'];

        return $this->checkSpeedRecords($rows);

        //经度，    纬度      时间         /速度*10/车头朝向
        //66804900|13707854|1568596149000|630|303|1|,0,|0|

    }

    public function uuid_v4()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function getHeader($cookie)
    {
        return array(
            'Accept:application/json, text/javascript, */*; q=0.01',
            'Accept-Encoding:gzip, deflate, br',
            'Accept-Language:zh-CN,zh;q=0.9',
            'Connection:keep-alive',
            'Content-Length:379',
            'Content-Type:application/x-www-form-urlencoded',
            'Cookie:JSESSIONID=' . $cookie,
            'Host:jg.gghypt.net',
            'Origin:https://jg.gghypt.net',
            'Referer:https://jg.gghypt.net/hyjg/monitorAction!monitor.action',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
            'X-Requested-With:XMLHttpRequest'
        );
    }

    public function getPostData($post_data)
    {

        foreach ( $post_data as $key => $value )
            $values [] =  $key."=" . urlencode ( $value );

        $data_string = implode ( "&" , $values ) ;
        return $data_string;
    }

    public function isOverspeed($str){

        $result = explode('|',$str);
        $speedRows = array();

        if (intval($result[3]) >1000){
            $temp['time'] = date('Y-m-d H:i:s', $result[2]/1000);
            $temp['speed'] = $result[3]/10;
            return $temp;
        }
        return false;
    }

    /**
     * @param $rows
     * @return array
     */
    public function checkSpeedRecords($rows)
    {
        $speedOver = array();
        $count = 0;
        foreach ($rows as $k => $v) {
            $temp = $this->isOverspeed($v);
            if ($temp != false) {
                $count++;
                $temp['id'] = $count;
                $speedOver[] = $temp;
            }
        }
        return $speedOver;
    }


    //this.params.vid + "_" + Math.floor(Math.random()) * 10000 + "_" + (new Date()).getTime()
    //5101808161579700227_0_1568603599272

    //Math.floor(Math.random()) * 10000
    //random 返回0-1之间。floor向下取整 永远都是0啊！这个兄弟括号打错地方了吧
    public function getQueryId($vid)
    {
        return $vid.'_'.'0'.'_'.intval(microtime(true)*1000);
    }

    /**
     * 输入间隔的小时数，返回开始的时间
     * @param $time
     * @return string
     */
    private function getStartTime($gapHour)
    {

        $now = time();
        $time = date('Y-m-d H:i:s',$now - $gapHour*60*60);
        $time = $time.':00';
        return $time;

    }


    private function getEndTime()
    {
        $time = date('Y-m-d H:i:s');
        $time = $time.':00';
        return $time;


    }
}