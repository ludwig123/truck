<?php

/**
 * Guide test case.
 */
use app\index\model\Guide;

class GuideTest extends PHPUnit_Framework_TestCase
{

    private $guide;

    protected function setUp()
    {
        parent::setUp();
        
    }

    protected function tearDown()
    {
        // TODO Auto-generated GuideTest::tearDown()
        $this->guide = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests Guide->__construct()
     */
    public function test__construct()
    {

    }


    public function testStart_talk()
    {
        $userInput = "逼";
        $guide = new Guide("oG24uwN10qZXaFm9KZLdeRj2inu0",$userInput);
        $reply = $guide->startTalk();
        $this->assertContains("您的输入不存在", $reply);
        
    }
    
    public function testCodeSearch(){
        $userInput = "11110";
        $guide = new Guide("oG24uwN10qZXaFm9KZLdeRj2inu0",$userInput);
        $reply = $guide->startTalk();
        $this->assertContains("11110", $reply);
        
        $userInput = "1";
        $guide = new Guide("oG24uwN10qZXaFm9KZLdeRj2inu0",$userInput);
        var_dump($guide);
        $reply = $guide->startTalk();
        $this->assertContains("第四十八条", $reply);
        
        $userInput = "11111";
        $guide = new Guide("oG24uwN10qZXaFm9KZLdeRj2inu0",$userInput);
        $reply = $guide->startTalk();
        $this->assertContains("11110", $reply);
        
        $userInput = "100元3分";
        $guide = new Guide("oG24uwN10qZXaFm9KZLdeRj2inu0",$userInput);
        $reply = $guide->startTalk();
        $this->assertContains("13130", $reply);

        $userInput = "111";
        $guide = new Guide("oG24uwN10qZXaFm9KZLdeRj2inu0",$userInput);
        $reply = $guide->startTalk();
        $this->assertContains("11150", $reply);
    }
    
    public function testCarSearch(){
        $userInput = "湘D9999922";
        $guide = new Guide("oG24uwN10qZXaFm9KZLdeRj2inu0",$userInput);
        $reply = $guide->startTalk();
        $this->assertContains("车牌后5位", $reply);
        
        $userInput = "湘DD999车";
        $guide = new Guide("oG24uwN10qZXaFm9KZLdeRj2inu0",$userInput);
        $reply = $guide->startTalk();
         $this->assertContains("车牌后5位", $reply);

        $userInput = "湘粤DD9999922";
        $guide = new Guide("oG24uwN10qZXaFm9KZLdeRj2inu0",$userInput);
        $reply = $guide->startTalk();
        $this->assertContains("车牌第2位", $reply);
        
        $userInput = "湘D99999";
        $guide = new Guide("oG24uwN10qZXaFm9KZLdeRj2inu0",$userInput);
        $reply = $guide->startTalk();
        $this->assertContains("未查询到接驳", $reply);
        
        $userInput = "沪DA3398";
        $guide = new Guide("oG24uwN10qZXaFm9KZLdeRj2inu0",$userInput);
        $reply = $guide->startTalk();
        $this->assertContains($userInput, $reply);

        
    }
    
}

