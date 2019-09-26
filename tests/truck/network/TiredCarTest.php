<?php


use app\truck\common\HistoryWarning;
use PHPUnit\Framework\TestCase;

class TiredCarTest extends TestCase
{

    public function testTiredHistroyWarnings()
    {
        \app\truck\common\NetWorker::setCookie('JSESSIONID=FC72C0E202AFCD260F6F990294DA4CEC; lineCheck=inLineCheck; __guid=149418029.2417622607162884600.1555312459470.336; JSESSIONID=BAAE08480C1794A3DCE5AEAA6397713A; monitor_count=6; COOKIE_USERID_HD=50a7a5b413295871aa0bd9578f5c835e04a7c2725fbd44dd6ce53268_1569507748133');
        $tiredWarings = new HistoryWarning();
        $carNum = '湘DB9333';
        $start_time_utc= '1569340800000';
        $end_time_utc  = '1569427199000';
        $rows = $tiredWarings->histroyTiredWarnings($carNum, $start_time_utc, $end_time_utc);
        $this->assertNotEmpty($rows);
    }

    public function testSpeedHistoryWarning()
    {
        \app\truck\common\NetWorker::setCookie('JSESSIONID=FC72C0E202AFCD260F6F990294DA4CEC; lineCheck=inLineCheck; __guid=149418029.2417622607162884600.1555312459470.336; JSESSIONID=BAAE08480C1794A3DCE5AEAA6397713A; monitor_count=6; COOKIE_USERID_HD=50a7a5b413295871aa0bd9578f5c835e04a7c2725fbd44dd6ce53268_1569507748133');
        $tiredWarings = new HistoryWarning();
        $carNum = '湘M69328';
        $start_time_utc= '1569168000000';
        $end_time_utc  = '1569254399000';
        $rows = $tiredWarings->histroySpeedWarnings($carNum, $start_time_utc, $end_time_utc);
        $this->assertNotEmpty($rows);
    }

    public function testSpeedHistoryWarning_noRecord_()
    {
        \app\truck\common\NetWorker::setCookie('JSESSIONID=FC72C0E202AFCD260F6F990294DA4CEC; lineCheck=inLineCheck; __guid=149418029.2417622607162884600.1555312459470.336; JSESSIONID=BAAE08480C1794A3DCE5AEAA6397713A; monitor_count=6; COOKIE_USERID_HD=50a7a5b413295871aa0bd9578f5c835e04a7c2725fbd44dd6ce53268_1569507748133');
        $tiredWarings = new HistoryWarning();
        $carNum = '湘M69328';
        $start_time_utc= '1569340800000';
        $end_time_utc  = '1569427199000';
        $rows = $tiredWarings->histroySpeedWarnings($carNum, $start_time_utc, $end_time_utc);
        $this->assertEmpty($rows);
    }

    public function testHistoryWarningBoth_return_array()
    {
        \app\truck\common\NetWorker::setCookie('JSESSIONID=FC72C0E202AFCD260F6F990294DA4CEC; lineCheck=inLineCheck; __guid=149418029.2417622607162884600.1555312459470.336; JSESSIONID=BAAE08480C1794A3DCE5AEAA6397713A; monitor_count=6; COOKIE_USERID_HD=50a7a5b413295871aa0bd9578f5c835e04a7c2725fbd44dd6ce53268_1569507748133');
        $tiredWarings = new HistoryWarning();
        $carNum = '湘M69328';
        $rows = $tiredWarings->warnings($carNum);
        $this->assertGreaterThan(3,count($rows));

    }
}
