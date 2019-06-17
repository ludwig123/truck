<?php

/**
 * Tokenizer test case.
 */
use app\index\model\Tokenizer;

class TokenizerTest extends PHPUnit_Framework_TestCase
{
    private $tokenizer;

    protected function setUp()
    {
        parent::setUp();
        
        
        $this->tokenizer = new Tokenizer();
    }

    protected function tearDown()
    {
        $this->tokenizer = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
    }

    public function testCodeTextSplit()
    {
        $text = "傻逼驾驶证";
       $result =  $this->tokenizer->split($text);
       $this->assertEquals("驾驶证", $result[0]);
       
       $text = "*_=/驾驶驾驶证我逾期";
       $result =  $this->tokenizer->split($text);
       $this->assertEquals("驾驶", $result[0]);
       $this->assertContains("逾期",$result[2]);
    }
    
    
    public function testLawInputSplit(){
        $text = "法15条";
        $result =  $this->tokenizer->split($text);

        $this->assertEquals("法", $result[0]);
        
        
        $text = "道交法 12 条";
        $result =  $this->tokenizer->split($text);
        $this->assertEquals("道交法", $result[0]);
        
        
        $text = "条例25条";
        $result =  $this->tokenizer->split($text);
        $this->assertEquals("条例", $result[0]);
    }
    
    public function testCodeNumSplit(){
        $text = "11110";
        $result =  $this->tokenizer->split($text);
        $this->assertEquals("11110", $result[0]);
        
    }
    
    public function testCodeMoneySplit(){
        $text = "100元";
        $result =  $this->tokenizer->split($text);
        $this->assertEquals("100元", $result[0]);
        
        
        //TODO 元会被去掉，应该正确的指示出来金额错误！
        $text = "110元";
        $result =  $this->tokenizer->split($text);
        $this->assertEquals("110", $result[0]);
//         $this->assertEquals("元", $result[1]);
        
    }
    
    public function testCodeScoreSplit(){
        $text = "12分";
        $result =  $this->tokenizer->split($text);
        $this->assertEquals("12分", $result[0]);
    }
    
    public function testCodeMixSplit(){
        $text = "100元12分11110驾驶证检验";
        $result =  $this->tokenizer->split($text);
        $this->assertEquals("100元", $result[0]);
        $this->assertEquals("12分", $result[1]);
        $this->assertEquals("11110", $result[2]);
        $this->assertEquals("驾驶证", $result[3]);
        $this->assertEquals("检验", $result[4]);
    }

}

