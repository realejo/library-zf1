<?php
require_once 'RW/View/Helper/PrintDate.php';
/**
 * RW_View_Helper_PrintDate test case.
 */
class PrintDateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_View_Helper_PrintDate
     */
    private $RW_View_Helper_PrintDate;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated PrintDateTest::setUp()
        $this->RW_View_Helper_PrintDate = new RW_View_Helper_PrintDate(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated PrintDateTest::tearDown()
        $this->RW_View_Helper_PrintDate = null;
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
     * Tests RW_View_Helper_PrintDate->printDate()
     */
    public function testPrintDate ()
    {
        // TODO Auto-generated PrintDateTest->testPrintDate()
        $this->markTestIncomplete("printDate test not implemented");
        $this->RW_View_Helper_PrintDate->printDate(/* parameters */);
    }
}

