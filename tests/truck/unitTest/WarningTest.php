<?php
use app\truck\controller\Warning;

require_once 'application/truck/controller/Warning.php';

/**
 * Warning test case.
 */
class WarningTest extends TestCase
{

    /**
     *
     * @var Warning
     */    private $warning;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated WarningTest::setUp()
        
        $this->warning = new Warning();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated WarningTest::tearDown()
        $this->warning = null;
        
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
     * Tests Warning->currentUTC()
     */
    public function testCurrentUTC()
    {

        $utc = $this->warning->currentUTC();
        echo $utc;
        
    }
}

