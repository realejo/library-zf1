<?php

namespace RWTest\View\Helper;

use RW_View_Helper_GetTime;

/**
 * RW_View_Helper_GetTime test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class GetTimeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RW_View_Helper_GetTime
     */
    private $RW_View_Helper_GetTime;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // TODO Auto-generated GetTimeTest::setUp()
        $this->RW_View_Helper_GetTime = new RW_View_Helper_GetTime(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        // TODO Auto-generated GetTimeTest::tearDown()
        $this->RW_View_Helper_GetTime = null;
        parent::tearDown();
    }

    public function testGetTime(): void
    {
        //Diferença de 10 segundos
        $segundos = date('Y-m-d H:i:s', strtotime('10 seconds ago'));
        $this->assertEquals('10 segundos', $this->RW_View_Helper_GetTime->getTime($segundos));

        //Diferença de 2 minutos
        $minutos = date('Y-m-d H:i:s', strtotime('2 minutes ago'));
        $this->assertEquals('2 minutos', $this->RW_View_Helper_GetTime->getTime($minutos));

        //Diferença de 2 horas
        $minutos = date('Y-m-d H:i:s', strtotime('2 hours ago'));
        $this->assertEquals('2 horas', $this->RW_View_Helper_GetTime->getTime($minutos));

        //Diferença de 2 dias
        $minutos = date('Y-m-d H:i:s', strtotime('48 hours ago'));
        $this->assertEquals('2 dias', $this->RW_View_Helper_GetTime->getTime($minutos));

        //Diferença de 2 dias
        $semanas = date('Y-m-d H:i:s', strtotime('16 days ago'));
        $this->assertEquals('2 semanas', $this->RW_View_Helper_GetTime->getTime($semanas));

        //Data há 1 Mês
        $mes = date('Y-m-d', strtotime('30 days ago'));
        $this->assertEquals('1 mês', $this->RW_View_Helper_GetTime->getTime($mes));

        //Data há 2 meses
        $meses = date('Y-m-d', strtotime('70 days ago'));
        $this->assertEquals('2 meses', $this->RW_View_Helper_GetTime->getTime($meses));

        //Data anos
        $ano = date('Y-m-d', strtotime('2 year sago'));
        $this->RW_View_Helper_GetTime->getTime($ano);
        $this->assertEquals('mais de um ano (31/12/1969 00:00:00)', $this->RW_View_Helper_GetTime->getTime($ano));
    }
}

