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
     * Tests Warning->index()
     */
    public function testIndex()
    {
        // TODO Auto-generated WarningTest->testIndex()
        $this->markTestIncomplete("index test not implemented");
        
        $this->warning->index(/* parameters */);
    }

    /**
     * Tests Warning->start()
     */
    public function testStart()
    {
        // TODO Auto-generated WarningTest->testStart()
        $this->markTestIncomplete("start test not implemented");
        
        $this->warning->start(/* parameters */);
    }

    /**
     * Tests Warning->car()
     */
    public function testCar()
    {
        // TODO Auto-generated WarningTest->testCar()
        $this->markTestIncomplete("car test not implemented");
        
        $this->warning->car(/* parameters */);
    }

    /**
     * Tests Warning->saveTiredWarning()
     */
    public function testSaveTiredWarning()
    {
        // TODO Auto-generated WarningTest->testSaveTiredWarning()
        $this->markTestIncomplete("saveTiredWarning test not implemented");
        
        $this->warning->saveTiredWarning(/* parameters */);
    }

    /**
     * Tests Warning->saveSpeedWarning()
     */
    public function testSaveSpeedWarning()
    {
        // TODO Auto-generated WarningTest->testSaveSpeedWarning()
        $this->markTestIncomplete("saveSpeedWarning test not implemented");
        
        $this->warning->saveSpeedWarning(/* parameters */);
    }

    /**
     * Tests Warning->getAddrList()
     */
    public function testGetAddrList()
    {
        // TODO Auto-generated WarningTest->testGetAddrList()
        $this->markTestIncomplete("getAddrList test not implemented");
        
        $this->warning->getAddrList(/* parameters */);
    }

    /**
     * Tests Warning->getDataAddr()
     */
    public function testGetDataAddr()
    {
        // TODO Auto-generated WarningTest->testGetDataAddr()
        $this->markTestIncomplete("getDataAddr test not implemented");
        
        $this->warning->getDataAddr(/* parameters */);
    }

    /**
     * Tests Warning->saveRows()
     */
    public function testSaveRows()
    {
        // TODO Auto-generated WarningTest->testSaveRows()
        $this->markTestIncomplete("saveRows test not implemented");
        
        $this->warning->saveRows(/* parameters */);
    }

    /**
     * Tests Warning->sentPost()
     */
    public function testSentPost()
    {
        // TODO Auto-generated WarningTest->testSentPost()
        $this->markTestIncomplete("sentPost test not implemented");
        
        $this->warning->sentPost(/* parameters */);
    }

    /**
     * Tests Warning->getCookie()
     */
    public function testGetCookie()
    {
        // TODO Auto-generated WarningTest->testGetCookie()
        $this->markTestIncomplete("getCookie test not implemented");
        
        $this->warning->getCookie(/* parameters */);
    }

    /**
     * Tests Warning->getUrl()
     */
    public function testGetUrl()
    {
        // TODO Auto-generated WarningTest->testGetUrl()
        $this->markTestIncomplete("getUrl test not implemented");
        
        $this->warning->getUrl(/* parameters */);
    }

    /**
     * Tests Warning->getFindAddressListUrl()
     */
    public function testGetFindAddressListUrl()
    {
        // TODO Auto-generated WarningTest->testGetFindAddressListUrl()
        $this->markTestIncomplete("getFindAddressListUrl test not implemented");
        
        $this->warning->getFindAddressListUrl(/* parameters */);
    }

    /**
     * Tests Warning->getStartTime()
     */
    public function testGetStartTime()
    {
        // TODO Auto-generated WarningTest->testGetStartTime()
        $this->markTestIncomplete("getStartTime test not implemented");
        
        $this->warning->getStartTime(/* parameters */);
    }

    /**
     * Tests Warning->getStep()
     */
    public function testGetStep()
    {
        // TODO Auto-generated WarningTest->testGetStep()
        $this->markTestIncomplete("getStep test not implemented");
        
        $this->warning->getStep(/* parameters */);
    }

    /**
     * Tests Warning->getHeader()
     */
    public function testGetHeader()
    {
        // TODO Auto-generated WarningTest->testGetHeader()
        $this->markTestIncomplete("getHeader test not implemented");
        
        $this->warning->getHeader(/* parameters */);
    }

    /**
     * Tests Warning->getDataSpeed()
     */
    public function testGetDataSpeed()
    {
        // TODO Auto-generated WarningTest->testGetDataSpeed()
        $this->markTestIncomplete("getDataSpeed test not implemented");
        
        $this->warning->getDataSpeed(/* parameters */);
    }

    /**
     * Tests Warning->getDataTired()
     */
    public function testGetDataTired()
    {
        // TODO Auto-generated WarningTest->testGetDataTired()
        $this->markTestIncomplete("getDataTired test not implemented");
        
        $this->warning->getDataTired(/* parameters */);
    }

    /**
     * Tests Warning->getLastWarning()
     */
    public function testGetLastWarning()
    {
        // TODO Auto-generated WarningTest->testGetLastWarning()
        $this->markTestIncomplete("getLastWarning test not implemented");
        
        $this->warning->getLastWarning(/* parameters */);
    }

    /**
     * Tests Warning->getEndTime()
     */
    public function testGetEndTime()
    {
        // TODO Auto-generated WarningTest->testGetEndTime()
        $this->markTestIncomplete("getEndTime test not implemented");
        
        $this->warning->getEndTime(/* parameters */);
    }

    /**
     * Tests Warning->getCookiesCache()
     */
    public function testGetCookiesCache()
    {
        // TODO Auto-generated WarningTest->testGetCookiesCache()
        $this->markTestIncomplete("getCookiesCache test not implemented");
        
        $this->warning->getCookiesCache(/* parameters */);
    }

    /**
     * Tests Warning->reduceRows()
     */
    public function testReduceRows()
    {
        // TODO Auto-generated WarningTest->testReduceRows()
        $this->markTestIncomplete("reduceRows test not implemented");
        
        $this->warning->reduceRows(/* parameters */);
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

