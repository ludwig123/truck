<?php


use app\truck\common\CarNumToVid;
use PHPUnit\Framework\TestCase;

class CarNumToVidTest extends TestCase
{

    public function testGetVid()
    {
        $carToVid = new CarNumToVid();
        $vid = $carToVid->getVid('湘A58969');

        $this->assertNotEmpty($vid);

    }
}
