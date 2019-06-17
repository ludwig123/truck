<?php

use app\index\model\CodeWaiter;

class CodeWaiterTest extends PHPUnit_Framework_TestCase
{

    private $codeWaiter;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated CodeWaiterTest::setUp()
        
        $this->codeWaiter = new CodeWaiter();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated CodeWaiterTest::tearDown()
        $this->codeWaiter = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    public function testReply()
    {
        
    }

}

