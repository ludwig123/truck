<?php

namespace app\truck\common;

use PHPUnit\Framework\TestCase;

class HistoryWarningTest extends TestCase
{

    public function testGapSecondsToReadableString()
    {
        $history = new HistoryWarning();
        $str = $history->gapSecondsToReadableString(863720);
        $this->assertContains('天',$str, '时间格式不正确');

    }
}
