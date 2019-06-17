<?php

/**
 * LawSearcher test case.
 */
use app\index\model\LawSearcher;

class LawSearcherTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var LawSearcher
     */
    private $lawSearcher;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->lawSearcher = new LawSearcher(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->lawSearcher = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

 
    public function testLaw()
    {
        $result = $this->lawSearcher->law('法', '99');
        $this->assertContains('第九十九条', $result);
    }

    public function testLaw_fa()
    {
        
        $index = 1;
        $result =  $this->lawSearcher->law("法规",$index);
        $this->assertContains("can't understand", $result, "应该提示不知道输入的是什么law name");
        
        $index = "200";
        $result =  $this->lawSearcher->law("道交法", $index);
        $this->assertContains("index should <=", $result);
        
        $index = -11;
        $result =  $this->lawSearcher->law("道交法", $index);
        $this->assertContains("index should >= 1", $result);
        
        $index = 1;
        $result =  $this->lawSearcher->law("法",$index);
        $this->assertContains('第一条', $result);
        
        $result =  $this->lawSearcher->law("办法",$index);
        $this->assertContains('第一条', $result);
        
        $result =  $this->lawSearcher->law("条例",$index);
        $this->assertContains('第一条', $result);
        
        $result =  $this->lawSearcher->law("机动车规定",$index);
        $this->assertContains('第一条', $result);
        
        $result =  $this->lawSearcher->law("驾驶证规定",$index);
        $this->assertContains('第一条', $result);
        
        $result =  $this->lawSearcher->law("校车规定",$index);
        $this->assertContains('第一条', $result);
    }
}

