<?php

/**
 * LawWaiter test case.
 */
use app\index\model\LawWaiter;

class LawWaiterTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var LawWaiter
     */
    private $lawWaiter;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated LawWaiterTest::setUp()
        
        $this->lawWaiter = new LawWaiter(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated LawWaiterTest::tearDown()
        $this->lawWaiter = null;
        
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
     * Tests LawWaiter->reply()
     */
    public function testReply()
    {
        $input = [
            0 => "法", 
            1 => "15"
        ];
        $result = $this->lawWaiter->reply($input);
        $this->assertEquals(15, 15, "应该查到第15条法律");
    }
}

