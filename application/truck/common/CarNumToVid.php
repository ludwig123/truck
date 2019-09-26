<?php


namespace app\truck\common;


/**
 * 通过车牌号获得车辆的VID
 *  VID是车辆在该系统的唯一识别码，用于查询其他信息
 * @package app\truck\common
 */
class CarNumToVid
{
    /** 输入车牌获取VID
     * @param string $carNum
     * @return string
     */
    public function getVid($carNum)
    {
        $carNum = '湘A58969';

        $cookie = 'JSESSIONID=74D6FF834386513ADD221BB3737B3F4D; lineCheck=inLineCheck; __guid=149418029.2417622607162884600.1555312459470.336; JSESSIONID=4ED1B7841F2BE239E11CF2ED13A67A51; monitor_count=14; COOKIE_USERID_HD=c76828f6628020f0b941a536ee9eb47e08da99393cf8b73b2e899264_1568800805702';

        $post_data['requestParam.like.vehicleNo'] = $carNum;
        $post_data['requestParam.equal.isOnline'] = 'all';
        $post_data['requestParam.equal.bgLevel'] = 'no';
        $post_data['undefined'] = 'undefined';
        $post_data['undefined'] = 'undefined';
        $post_data['requestParam.equal.orgId'] = '430000';
        $post_data['undefined'] = 'undefined';
        $post_data['undefined'] = 'undefined';
        $post_data['undefined'] = 'undefined';
        $post_data['requestParam.equal.orgLevel'] = '1';
        $post_data['undefined'] = 'undefined';
        $post_data['undefined'] = 'undefined';
        $post_data['undefined'] = 'undefined';
        $post_data['undefined'] = 'undefined';
        $post_data['requestParam.page'] = '1';
        $post_data['requestParam.rows'] = '20';
        $post_data['sortname'] = 'areaName';
        $post_data['sortorder'] = 'asc';



        //$cookie = NetWorker::getCookiesCache();
        $worker = new NetWorker();
        $data=$this->getPostData($post_data);
        $len = strlen($data);
        $header = $this->getHeader($cookie, $len);

        //$data = 'requestParam.equal.id=6239080096271702727&requestParam.equal.startTime=2019-09-15+12%3A38%3A33%3A00&requestParam.equal.endTime=2019-09-16+12%3A38%3A33%3A00&requestParam.equal.orgCode=430000&requestParam.equal.queryId=6239080096271702727_0_1568608727202&requestParam.equal.init=1&requestParam.equal.trailDataKey=1570A981-AF68-4607-ACBF-AA1B141AFC38&requestParam.equal.searchType=1';
        $url = URLRepository::findBasicInfoVehicleUrl();
        $response = $worker->sentPost($header, $data, $url);
        $records = json_decode($response, true);
        $rows = $records['Rows'];
        $vid = $rows[0]['vid'];
        return $vid;

    }

    /** 把postdata 键值对转为 url的字符串
     * @param $post_data
     * @return string
     */
    public function getPostData($post_data)
    {

        foreach ($post_data as $key => $value)
            $values [] = $key . "=" . urlencode($value);

        $data_string = implode("&", $values);
        return $data_string;
    }


    public function getheader($cookie,$len)
    {
        return array(
            'Accept:application/json, text/javascript, */*; q=0.01',
            'Accept-Encoding:gzip, deflate, br',
            'Accept-Language:zh-CN,zh;q=0.9',
            'Connection:keep-alive',
            'Content-Length:'.$len,
            'Content-Type:application/x-www-form-urlencoded',
            'Cookie:'.$cookie,
            'Host:jg.gghypt.net',
            'Origin:https://jg.gghypt.net',
            'Referer:https://jg.gghypt.net/hyjg/monitorAction!monitor.action',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
            'X-Requested-With:XMLHttpRequest'
        );
    }


}