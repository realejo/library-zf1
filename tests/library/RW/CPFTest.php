<?php
/**
 * RW_CPF test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
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

