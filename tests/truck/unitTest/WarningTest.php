<?php
use app\truck\controller\Warning;

require_once 'application/truck/controller/Warning.php';

/**
 * Warning test case.
 */
class WarningTest extends \think\testing\TestCase
{

    /**
     *
     * @var Warning
     */    private $warning;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp():void
    {
        parent::setUp();
        
        // TODO Auto-generated WarningTest::setUp()
        
        $this->warning = new Warning();

    }



    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }




    /**
     * Tests Warning->currentUTC()
     */
    public function testCurrentUTC()
    {

        $utc = $this->warning->currentUTC();
        echo $utc;
        
    }
}

