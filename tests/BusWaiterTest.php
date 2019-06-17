<?php

/**
 * BusWaiter test case.
 */
use app\index\model\BusWaiter;

class BusWaiterTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var BusWaiter
     */
    private $busWaiter;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated BusWaiterTest::setUp()
        
        $this->busWaiter = new BusWaiter(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated BusWaiterTest::tearDown()
        $this->busWaiter = null;
        
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
     * Tests BusWaiter->reply()
     */
    public function testReply()
    {

    }
}

