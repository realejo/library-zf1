<?php
require_once 'RW/View/Helper/GetTime.php';
/**
 * RW_View_Helper_GetTime test case.
 */
class GetTimeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_View_Helper_GetTime
     */
    private $RW_View_Helper_GetTime;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated GetTimeTest::setUp()
        $this->RW_View_Helper_GetTime = new RW_View_Helper_GetTime(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated GetTimeTest::tearDown()
        $this->RW_View_Helper_GetTime = null;
        parent::tearDown();
    }
    /**
     * Constructs the test case.
     */
    public function __construct ()
    {
        // TODO Auto-generated constructor
    }
    /**
     * Tests RW_View_Helper_GetTime->getTime()
     */
    public function testGetTime ()
    {
        // TODO Auto-generated GetTimeTest->testGetTime()
        $this->markTestIncomplete("getTime test not implemented");
        $this->RW_View_Helper_GetTime->getTime(/* parameters */);
    }
}

