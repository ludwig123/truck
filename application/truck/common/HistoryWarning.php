<?php


namespace app\truck\common;


class HistoryWarning
{
    public function warnings($carNum, $day)
    {



        $warnings = array();
        for ($i = 0; $i < $day; $i++)
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
        $formateWarnings = $this->beautifyRows($warnings);
        return $formateWarnings;

    }

    public function histroySpeedWarnings($carNum, $start_time_utc, $end_time_utc)
    {

        $speed = '101';

        $cookie = NetWorker::getCookiesCache();
        $post_data['requestParam.like.vehicleNo'] = $carNum;
        $post_data['requestParam.equal.alarmCode'] = '1';
        $post_data['requestParam.equal.gpsSpeed'] = $speed;
        $post_data['undefined'] = 'undefined';
        $post_data['requestParam.equal.startTimeUtc'] = $start_time_utc;
        $post_data['requestParam.equal.endTimeUtc'] = $end_time_utc;
        //$post_data['requestParam.equal.areaCode'] = '430000';
        $post_data['undefined'] = 'undefined';
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
        //$post_data['requestParam.equal.areaCode'] = '430000';
        $post_data['undefined'] = 'undefined';
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

    private function beautifyRows($rows)
    {
        $beautifyRows = array();
        if (empty($rows))
        {
            return array();
        }

        foreach ($rows as $k => $v)
        {
            $beautifyRows[] = $this->beautifyRow($v);
        }

        return $beautifyRows;

    }

    private function beautifyRow($row)
    {
        $beautify = array();
        if (intval($row['alarmCode'] = 2))
        {
            $beautify['type'] = '疲劳';
        }
        else{
            $beautify['type'] = '超速';
        }


        $beautify['carNum'] = $row['vehicleNo'];
        $beautify['owner'] = $row['companyname'];
        $beautify['alarmTime'] = $this->alarmTime($row);
        $beautify['durationTime'] = $this->calcDruation($row);
        $beautify['limitSpeed'] = intval($row['limitSpeed']);
        $beautify['currentSpeed'] = intval($row['gpsSpeed'])/10;
        $beautify['address'] = $row['alarmAddr'];
        return $beautify;

    }

    private function calcDruation($row)
    {
        $start = intval($row['alarmStartUtc']);

        if (! is_numeric($row['alarmEndUtc']))
        {
            return '违法持续中';
        }
        $end = intval($row['alarmEndUtc']);
        $gap = ($end - $start)/1000;

        $str = $this->gapSecondsToReadableString($gap);
        return $str;



    }


    private function alarmTime($row)
    {
        $timeMicroUTC = $row['utc'];
        $alarmTime = TimeTranslator::microUtcToDateTime(intval($timeMicroUTC));
        return $alarmTime;
    }

    private function gapDay($seconds){
        $days = floor($seconds / 86400);
        return $days;
    }
    private function gapHour($seconds)
    {
        $seconds = $seconds % 86400;
        $hours = floor($seconds / 3600);
        return $hours;

    }

    private function gapMinute($seconds)
    {
        $seconds = $seconds % 3600;
        $minute = floor($seconds / 60);
        return $minute;

    }

    private function gapSecond($seconds)
    {
        $seconds = $seconds % 60;
        return $seconds;
    }

    /** 把秒数字转为X天X小时X分X秒
     * @param $seconds
     * @return string
     */
    public function gapSecondsToReadableString($seconds)
    {
        $day = $this->gapDay($seconds);
        $hour = $this->gapHour($seconds);
        $minute = $this->gapMinute($seconds);
        $second = $this->gapSecond($seconds);

        $str = '';
        if ($day > 0 )
        {
            $str = $day.'天';
        }

        if ($hour>0)
        {
            $str = $str .$hour . '小时';
        }

        if ($minute>0)
        {
            $str = $str. $minute . '分钟';
        }

        if ($second>0)
        {
            $str = $str .$second. '秒';
        }

        return $str;

    }
}