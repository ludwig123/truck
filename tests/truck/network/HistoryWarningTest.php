<?php


use app\truck\common\HistoryWarning;
use PHPUnit\Framework\TestCase;

class HistoryWarningTest extends TestCase
{

    public function testIsTruckExist()
    {
        \app\truck\common\NetWorker::setCookie('JSESSIONID=BAB4D1B88C1FC133CA6DD79983176F75; __guid=149418029.1707435643247522000.1566691178646.047; JSESSIONID=4932B2600699A5487403CB3D58A9198C; monitor_count=2; COOKIE_USERID_HD=42f8f929f3c4865737441b73f58d8a4a79bafa6e010915486fd00189_1570070560186');

        $tiredWarings = new HistoryWarning();
        $carNum = '湘DB9333';
        $carInfo = $tiredWarings->isTruckExist($carNum);
        $this->assertTrue($carInfo);
    }

    public function testIsTruck_Not_Exist()
    {
        \app\truck\common\NetWorker::setCookie('JSESSIONID=BAB4D1B88C1FC133CA6DD79983176F75; __guid=149418029.1707435643247522000.1566691178646.047; JSESSIONID=4932B2600699A5487403CB3D58A9198C; monitor_count=2; COOKIE_USERID_HD=42f8f929f3c4865737441b73f58d8a4a79bafa6e010915486fd00189_1570070560186');

        $tiredWarings = new HistoryWarning();
        $carNum = '湘SSB9994';
        $carInfo = $tiredWarings->isTruckExist($carNum);
        $this->assertFalse($carInfo);
    }


}
