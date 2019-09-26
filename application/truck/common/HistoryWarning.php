<?php


namespace app\truck\common;


class HistoryWarning
{
    public function warnings($carNum)
    {
        $days = 7;
        $warnings = array();
        for ($i = 0; $i < $days; $i++)
        {
            $end_time_utc = TimeTranslator::todayEnd() - 86400000 * $i;
            $start_time_utc = $end_time_utc - 86399000;
            sleep(0.2);
            $speedRows = $this->histroySpeedWarnings($carNum, $start_time_utc, $end_time_utc);
            if (!empty($speedRows))
            {
                foreach ($speedRows as $k=>$v)
                {
                    $warnings[] = $v;
                }
            }
            sleep(0.2);
            $tiredRows = $this->histroyTiredWarnings($carNum, $start_time_utc, $end_time_utc);
            if (!empty($tiredRows))
            {
                foreach ($tiredRows as $k=>$v)
                {
                    $warnings[] = $v;
                }
            }
        }
        return $warnings;

    }

    public function histroySpeedWarnings($carNum, $start_time_utc, $end_time_utc)
    {

        $speed = '103';
        $cookie = NetWorker::getCookiesCache();
        $post_data['requestParam.like.vehicleNo'] = $carNum;
        $post_data['requestParam.equal.alarmCode'] = '1';
        $post_data['requestParam.equal.gpsSpeed'] = $speed;
        $post_data['undefined'] = 'undefined';
        $post_data['requestParam.equal.startTimeUtc'] = $start_time_utc;
        $post_data['requestParam.equal.endTimeUtc'] = $end_time_utc;
        $post_data['requestParam.equal.areaCode'] = '430000';
        $post_data['undefined'] = 'undefined';
        $post_data['undefined'] = 'undefined';
        $post_data['requestParam.page'] = '1';
        $post_data['requestParam.rows'] = '20';
        $post_data['sortname'] = 'vehicleNo';
        $post_data['sortorder'] = 'asc';


        $data=$this->arrayToPostString($post_data);

        $header = NetWorker::getHeader($cookie, $data);
        $url = URLRepository::vehicleAlarmUrl();

        $resultStr = NetWorker::getPostResult($header, $data, $url);
        if ($resultStr == ''){
            echo 'sessinId 过期！';
            exit();
        }
        $result = json_decode($resultStr, true);
        $rows = $result['Rows'];

        // 服务器没有返回数据
        if (empty($rows))
            return 0;

        $filter = '衡阳市';
        $rows = LocationTranslator::addAddress($rows);
        return $rows;

    }

    public function histroyTiredWarnings($carNum, $start_time_utc, $end_time_utc)
    {

        $cookie = NetWorker::getCookiesCache();
        $post_data['requestParam.like.vehicleNo'] = $carNum;
        $post_data['requestParam.equal.alarmCode'] = '2';
        $post_data['undefined'] = 'undefined';
        $post_data['undefined'] = 'undefined';
        $post_data['requestParam.equal.startTimeUtc'] = $start_time_utc;
        $post_data['requestParam.equal.endTimeUtc'] = $end_time_utc;
        $post_data['requestParam.equal.areaCode'] = '430000';
        $post_data['undefined'] = 'undefined';
        $post_data['undefined'] = 'undefined';
        $post_data['requestParam.page'] = '1';
        $post_data['requestParam.rows'] = '20';
        $post_data['sortname'] = 'vehicleNo';
        $post_data['sortorder'] = 'asc';

        $data=$this->arrayToPostString($post_data);

        $header = NetWorker::getHeader($cookie, $data);
        $url = URLRepository::vehicleAlarmUrl();

        $resultStr = NetWorker::getPostResult($header, $data, $url);
        if ($resultStr == ''){
            echo 'sessinId 过期！';
            exit();
        }
        $result = json_decode($resultStr, true);
        $rows = $result['Rows'];

        // 服务器没有返回数据
        if (empty($rows))
            return 0;

        $filter = '衡阳市';
        $rows = LocationTranslator::addAddress($rows);
        return $rows;

    }



    private function arrayToPostString($dataArr)
    {

        foreach ($dataArr as $key => $value)
            $values [] = $key . "=" . urlencode($value);

        $data_string = implode("&", $values);
        return $data_string;

    }

}