<?php
require_once 'RW/SimpleXMLExtended.php';
/**
 * RW_SimpleXMLExtended test case.
 */
class SimpleXMLExtendedTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_SimpleXMLExtended
     */
    private $RW_SimpleXMLExtended;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated SimpleXMLExtendedTest::setUp()
        $this->RW_SimpleXMLExtended = new RW_SimpleXMLExtended(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated SimpleXMLExtendedTest::tearDown()
        $this->RW_SimpleXMLExtended = null;
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
     * Tests RW_SimpleXMLExtended->addCData()
     */
    public function testAddCData ()
    {
        // TODO Auto-generated SimpleXMLExtendedTest->testAddCData()
        $this->markTestIncomplete("addCData test not implemented");
        $this->RW_SimpleXMLExtended->addCData(/* parameters */);
    }
}

