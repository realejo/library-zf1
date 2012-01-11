<?php
/**
 * RW_Math test case.
 *
 * @category   RW
 * @package    RW_Math
 * @subpackage UnitTests
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */

require_once 'RW/Math.php';

class MathTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_Math
     */
    private $RW_Math;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated MathTest::setUp()
        $this->RW_Math = new RW_Math(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated MathTest::tearDown()
        $this->RW_Math = null;
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
     * Tests RW_Math::moda()
     */
    public function testModa ()
    {
        // Valores inválidos
        // @todo como testar exceptions?
        //$this->getExpectedException($this->RW_Math->moda(1));
        //$this->getExpectedException($this->RW_Math->moda(null));
        //$this->getExpectedException($this->RW_Math->moda(new stdClass()));
        //$this->getExpectedException($this->RW_Math->moda('oi'));

        // Valores válidos
        $this->assertTrue(count($this->RW_Math->moda(array()))===0);
        $this->assertEquals(array(1),   $this->RW_Math->moda(array(1,1,2)));
        $this->assertEquals(array(1),   $this->RW_Math->moda(array(1,1,1,2,2,3)));
        $this->assertEquals(array(1,2), $this->RW_Math->moda(array(1,2)));
        $this->assertEquals(array(1,2), $this->RW_Math->moda(array(1,1,2,2)));
        $this->assertEquals(array(1,2), $this->RW_Math->moda(array(1,1,2,2,3,4)));
        $this->assertEquals(array(1,2), $this->RW_Math->moda(array(1,2,1,3,4,1,2,2,3)));
    }
    /**
     * Tests RW_Math::mediana()
     */
    public function testMediana ()
    {
        // Valores inválidos
        // @todo como testar exceptions?
        $this->assertFalse($this->RW_Math->mediana(array()));

        // Valores válidos
        $this->assertTrue(count($this->RW_Math->moda(array()))===0);
        $this->assertEquals(1,   $this->RW_Math->mediana(array(1,1,2)));
        $this->assertEquals(1.5, $this->RW_Math->mediana(array(1,1,1,2,2,3)));
        $this->assertEquals(1.5, $this->RW_Math->mediana(array(1,2)));
        $this->assertEquals(1.5, $this->RW_Math->mediana(array(1,1,2,2)));
        $this->assertEquals(2,   $this->RW_Math->mediana(array(1,1,2,2,3,4)));
        $this->assertEquals(2,   $this->RW_Math->mediana(array(1,2,1,3,4,1,2,2,3)));
        $this->assertEquals(2,   $this->RW_Math->mediana(array(1,2,1,3,4,1,2,2,3),'strcmp'));
    }
}

