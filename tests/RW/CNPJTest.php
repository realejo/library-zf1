<?php
require_once 'RW/CNPJ.php';
/**
 * RW_CNPJ test case.
 */
class CNPJTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_CNPJ
     */
    private $RW_CNPJ;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated CNPJTest::setUp()
        $this->RW_CNPJ = new RW_CNPJ(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated CNPJTest::tearDown()
        $this->RW_CNPJ = null;
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
     * Tests RW_CNPJ::valid()
     */
    public function testValid ()
    {
		$this->assertTrue(RW_CNPJ::valid('00577646000136'));
		$this->assertTrue(RW_CNPJ::valid('00.577.646/0001-36'));
		$this->assertTrue(!RW_CNPJ::valid('10.577.646/0001-36'));
		$this->assertTrue(RW_CNPJ::valid('0.577.646/0001-36'));
    }
    /**
     * Tests RW_CNPJ::format()
     */
    public function testFormat ()
    {
		$this->assertTrue(RW_CNPJ::format('00577646000136')=== '00.577.646/0001-36');
    }
}

