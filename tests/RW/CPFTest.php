<?php
/**
 * RW_CPF test case.
 *
 * @category   RW
 * @package    RW_CPF
 * @subpackage UnitTests
 * @author     Realejo
 * @version    $Id: CPFTest.php 7 2012-01-11 17:15:57Z rodrigo $
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */

require_once 'RW/CPF.php';

class CPFTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_CPF
     */
    private $RW_CPF;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated CPFTest::setUp()
        $this->RW_CPF = new RW_CPF(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated CPFTest::tearDown()
        $this->RW_CPF = null;
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
     * Tests RW_CPF::valid()
     */
    public function testValid ()
    {
		$this->assertTrue(RW_CPF::valid('06003014601'));
		$this->assertFalse(RW_CPF::valid('111.111.111-01'));
    }
    /**
     * Tests RW_CPF::format()
     */
    public function testFormat ()
    {
		$this->assertEquals('060.030.146-01', RW_CPF::format('06003014601'));
    }
}

