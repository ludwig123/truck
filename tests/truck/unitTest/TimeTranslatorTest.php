<?php


use app\truck\common\TimeTranslator;
use PHPUnit\Framework\TestCase;

class TimeTranslatorTest extends TestCase
{

    public function testTodayEnd()
    {
        $todyEnd = TimeTranslator::todayEnd();
        $this->assertEquals(1569513599000, $todyEnd);

    }
}
