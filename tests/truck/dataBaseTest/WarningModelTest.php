<?php
use PHPUnit\Framework\TestCase;
use app\truck\model\WarningModel;

require_once 'application/truck/model/WarningModel.php';

/**
 * WarningModel test case.
 */
class WarningModelTest extends TestCase
{

    /**
     *
     * @var WarningModel
     */
    private $warningModel;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated WarningModelTest::setUp()
        
        $this->warningModel = new WarningModel(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated WarningModelTest::tearDown()
        $this->warningModel = null;
        
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
     * Tests WarningModel->__construct()
     */
    public function test__construct()
    {
        // TODO Auto-generated WarningModelTest->test__construct()
        $this->markTestIncomplete("__construct test not implemented");
        
        $this->warningModel->__construct(/* parameters */);
    }
    
    /**
     * @testdox
     * 
     */
    public function testGetTiredWarningCars(){
        $from = '1557682110000';
        $end = '1558840270797'; //5-26
        
       $tiredTruck =  $this->warningModel->tiredWarnings($from, $end);
       $this->assertNotNull($tiredTruck, '不应该没有疲劳数据');
    }
    

  
}

