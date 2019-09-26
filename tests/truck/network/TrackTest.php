<?php


use app\truck\common\Track;
use PHPUnit\Framework\TestCase;

class TrackTest extends TestCase
{

    public function testFindTrack()
    {
        $track = new Track();
        $response = $track->findTrack('湘D9999',24);
        $this->assertNotEmpty($response);

    }

    public function testOverSpeedRecord(){
        $track = new Track();
        $records = array(
            '66804900|13707854|1568596149000|630|303|1|,0,|0|',
            '66804900|13707854|1568596149000|1100|303|1|,0,|0|'
        );

        $speedRecords = $track->checkSpeedRecords($records);
        $this->assertEquals(1,count($speedRecords));
    }
}
