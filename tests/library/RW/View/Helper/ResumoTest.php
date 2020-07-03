<?php

namespace RWTest\View\Helper;

use RW_View_Helper_Resumo;

/**
 * RW_View_Helper_Resumo test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */

class ResumoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RW_View_Helper_Resumo
     */
    private $RW_View_Helper_Resumo;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        // TODO Auto-generated ResumoTest::setUp()
        $this->RW_View_Helper_Resumo = new RW_View_Helper_Resumo(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated ResumoTest::tearDown()
        $this->RW_View_Helper_Resumo = null;
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests RW_View_Helper_Resumo->resumo()
     */
    public function testResumo()
    {
        // TODO Auto-generated ResumoTest->testResumo()
        //$this->markTestIncomplete("resumo test not implemented");
        $dados = 'Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
			        Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
			        Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
			        Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
			        Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum';
        $texto = $this->RW_View_Helper_Resumo->resumo($dados);
        $this->assertEquals('197', strlen($texto));
    }
}

