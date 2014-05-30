<?php
/**
 * RW_SimpleXMLExtended test case.
 *
 * @category   RW
 * @package    RW_SimpleXMLExtended
 * @subpackage UnitTests
 * @author     Realejo
 * @version    $Id: SimpleXMLExtendedTest.php 7 2012-01-11 17:15:57Z rodrigo $
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */

require_once 'RW/SimpleXMLExtended.php';

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

