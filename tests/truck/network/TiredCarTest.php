<?php


use app\truck\common\HistoryWarning;
use PHPUnit\Framework\TestCase;

class TiredCarTest extends TestCase
{
    protected function setUp():void
    {
        parent::setUp();
        $cookie = 'JSESSIONID=348E0DEDB31C9D29A1A9F55FA902712E; lineCheck=inLineCheck; __guid=149418029.2417622607162884600.1555312459470.336; JSESSIONID=C72A3228F2F867921A82FE816EE7B9E3; COOKIE_USERID_HD=c73100a3f5074480a68e69c33661dfabf37f6fb27309b072286a92e7_1569556088634; monitor_count=11';
        \app\truck\common\NetWorker::setCookie($cookie);

    }

    public function testTiredHistroyWarnings()
    {
        $tiredWarings = new HistoryWarning();
        $carNum = '湘DB9333';
        $start_time_utc= '1569340800000';
        $end_time_utc  = '1569427199000';
        $rows = $tiredWarings->histroyTiredWarnings($carNum, $start_time_utc, $end_time_utc);
        $this->assertNotEmpty($rows);
    }

    public function testSpeedHistoryWarning()
    {
        $tiredWarings = new HistoryWarning();
        $carNum = '湘M69328';
        $start_time_utc= '1569168000000';
        $end_time_utc  = '1569254399000';
        $rows = $tiredWarings->histroySpeedWarnings($carNum, $start_time_utc, $end_time_utc);
        $this->assertNotEmpty($rows);
    }

    public function testSpeedHistoryWarning_noRecord_()
    {
        $tiredWarings = new HistoryWarning();
        $carNum = '湘M69328';
        $start_time_utc= '1569340800000';
        $end_time_utc  = '1569427199000';
        $rows = $tiredWarings->histroySpeedWarnings($carNum, $start_time_utc, $end_time_utc);
        $this->assertEmpty($rows);
    }

    public function testHistoryWarningBoth_return_array()
    {
        $tiredWarings = new HistoryWarning();
        $carNum = '湘M69328';
        $rows = $tiredWarings->warnings($carNum);
        $this->assertGreaterThan(3,count($rows));

    }
}
