<?php


namespace app\truck\common;


class LocationTranslator
{
    /**
     * @param $rows
     * @param $filter
     * @return array
     */
    public static function addAddress($rows, $filter)
    {
//通过经纬度查询具体的地址
        $addrListStr = self::getAddrList($rows);
        $addrList = json_decode($addrListStr, true);
        $rows = self::addAddrToAlarmList($addrList, $rows);
        $rows = self::reduceRows($rows, $filter);
        return $rows;
    }

    private static function addAddrToAlarmList($addrList, $rows){
        $records = array();
        foreach ($rows as $k => $row){
            $key = array_search($row['alarmId'], array_column($addrList, 'key'));
            $addr = $addrList[$key]['values'];
            $row['alarmAddr']  = $addr;
            $records[]=$row;
        }
        return $records;
    }

    public static function getAddrList($rows){
        $data = self::getDataAddr($rows);

        $cookie = self::getCookiesCache();
        $header = self::getHeader($cookie, $data);
        $url = URLRepository::findAddrByLatitudeUrl();

        $result = NetWorker::getPostResult($header, $data, $url);
        return $result;
    }

    public static function reduceRows($rows, $destination){
        $result = array();
        foreach ($rows as $k => $v){
            if (preg_match('/'.$destination.'/u', $v['alarmAddr'])){
                $result[] = $v;
            }
        }
        return $result;
    }

    public static function getDataAddr($rows){
        $data = 'latLon=';
        foreach ($rows as $k => $row){
            $data .= $row['alarmId'].'%2C'.($row['maplon']/600000).'%2C'.($row['maplat']/600000).'%3B';
        }

        return $data;
    }

    /** 从cache中获取 cookie
     * @return mixed|string
     */
    public static function getCookiesCache(){
        $cookie = cache('truckCookie');
        if ($cookie == null){
            return 'empty_cookie';
        }

        else
            return $cookie;
    }

    public static function getHeader($cookie, $data)
    {
        $dataLen = strlen($data);
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
}