<?php


namespace app\truck\common;


class Track
{
    public function findTrack()
    {
        $startTime = '2019-09-15 10:44:25';
        $endTime = '2019-09-16 10:44:25';
        $cookie = 'JSESSIONID=7A0C744F742FF8D1D0D6A052F9C18188; lineCheck=inLineCheck; __guid=149418029.2417622607162884600.1555312459470.336; JSESSIONID=666F96E503340F737C40A1C4EE0A893C; COOKIE_USERID_HD=35c9f7c6046599987bb168f39ffa7846d1055c76fd480ae9c9d6a829_1568682313059; monitor_count=6';

        $worker = new NetWorker();
        $header = $this->getHeader($cookie);
        $data = 'requestParam.equal.id=6239080096271702727&requestParam.equal.startTime=2019-09-15+12%3A38%3A33%3A00&requestParam.equal.endTime=2019-09-16+12%3A38%3A33%3A00&requestParam.equal.orgCode=430000&requestParam.equal.queryId=6239080096271702727_0_1568608727202&requestParam.equal.init=1&requestParam.equal.trailDataKey=1570A981-AF68-4607-ACBF-AA1B141AFC38&requestParam.equal.searchType=1';
        $url = URLRepository::findTrackUrl();
        $response = $worker->sentPost($header, $data, $url);
        $records = json_decode($response, true);
        $rows = $records['Rows'];

        return $this->checkSpeedRecords($rows);

        //经度，    纬度      时间         /速度*10/车头朝向
        //66804900|13707854|1568596149000|630|303|1|,0,|0|

    }

    public static function uuid_v4()
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

    public function getPostData()
    {

        return array(
            'requestParam.equal.endTime:"2019-09-16 10:44:25:00"',
            'requestParam.equal.id:5101808161579700227',
            'requestParam.equal.init:1',
            'requestParam.equal.orgCode:430000',
            'requestParam.equal.queryId:5101808161579700227_0_1568603599272',
            'requestParam.equal.searchType:1',
            'requestParam.equal.startTime:2019-09-15 10:44:25:00',
            'requestParam.equal.trailDataKey:4959DB80-8195-4D96-9A6D-FDDCE107F656');
    }

    public function isOverspeed($str){

        $result = explode('|',$str);

        if (intval($result[3]) >1000){
            $result[2] = date('Y-m-d H:i:s', $result[2]/1000);
            $result[3] = $result[3]/10;
            return $result;
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
        foreach ($rows as $k => $v) {
            $temp = $this->isOverspeed($v);
            if ($temp != false) {
                $speedOver[] = $temp;
            }
        }
        return $speedOver;
    }
}