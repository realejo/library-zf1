<?php

namespace RWTest;

use PHPUnit\Framework\TestCase;
use RW_Math;

/**
 * RW_Math test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class MathTest extends TestCase
{
    /**
     * @var RW_Math
     */
    private $RW_Math;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->RW_Math = new RW_Math(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated MathTest::tearDown()
        $this->RW_Math = null;
        parent::tearDown();
    }

    /**
     * Tests RW_Math::moda()
     */
    public function testModa()
    {
        // Valores inválidos
        // @todo como testar exceptions?
        //$this->getExpectedException($this->RW_Math->moda(1));
        //$this->getExpectedException($this->RW_Math->moda(null));
        //$this->getExpectedException($this->RW_Math->moda(new stdClass()));
        //$this->getExpectedException($this->RW_Math->moda('oi'));

        // Valores válidos
        $this->assertTrue(count($this->RW_Math->moda(array())) === 0);
        $this->assertEquals(array(1), $this->RW_Math->moda(array(1, 1, 2)));
        $this->assertEquals(array(1), $this->RW_Math->moda(array(1, 1, 1, 2, 2, 3)));
        $this->assertEquals(array(1, 2), $this->RW_Math->moda(array(1, 2)));
        $this->assertEquals(array(1, 2), $this->RW_Math->moda(array(1, 1, 2, 2)));
        $this->assertEquals(array(1, 2), $this->RW_Math->moda(array(1, 1, 2, 2, 3, 4)));
        $this->assertEquals(array(1, 2), $this->RW_Math->moda(array(1, 2, 1, 3, 4, 1, 2, 2, 3)));
    }

    /**
     * Tests RW_Math::mediana()
     */
    public function testMediana()
    {
        // Valores inválidos
        // @todo como testar exceptions?
        $this->assertFalse($this->RW_Math->mediana(array()));

        // Valores válidos
        $this->assertTrue(count($this->RW_Math->moda(array())) === 0);
        $this->assertEquals(1, $this->RW_Math->mediana(array(1, 1, 2)));
        $this->assertEquals(1.5, $this->RW_Math->mediana(array(1, 1, 1, 2, 2, 3)));
        $this->assertEquals(1.5, $this->RW_Math->mediana(array(1, 2)));
        $this->assertEquals(1.5, $this->RW_Math->mediana(array(1, 1, 2, 2)));
        $this->assertEquals(2, $this->RW_Math->mediana(array(1, 1, 2, 2, 3, 4)));
        $this->assertEquals(2, $this->RW_Math->mediana(array(1, 2, 1, 3, 4, 1, 2, 2, 3)));
        $this->assertEquals(2, $this->RW_Math->mediana(array(1, 2, 1, 3, 4, 1, 2, 2, 3), 'strcmp'));
    }
}

