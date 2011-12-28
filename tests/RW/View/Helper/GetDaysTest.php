<?php
require_once 'RW/View/Helper/GetDays.php';
/**
 * RW_View_Helper_GetDays test case.
 */
class GetDaysTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_View_Helper_GetDays
     */
    private $RW_View_Helper_GetDays;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated GetDaysTest::setUp()
        $this->RW_View_Helper_GetDays = new RW_View_Helper_GetDays(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated GetDaysTest::tearDown()
        $this->RW_View_Helper_GetDays = null;
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
     * Tests RW_View_Helper_GetDays->getDays()
     */
    public function testGetDays ()
    {
        // TODO Auto-generated GetDaysTest->testGetDays()
        $this->markTestIncomplete("getDays test not implemented");
        $this->RW_View_Helper_GetDays->getDays(/* parameters */);
    }
}

