<?php

namespace RWTest\View\Helper;

use PHPUnit\Framework\TestCase;
use RW_View_Helper_GetDays;

/**
 * RW_View_Helper_GetDays test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class GetDaysTest extends TestCase
{
    /**
     * @var RW_View_Helper_GetDays
     */
    private $RW_View_Helper_GetDays;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // TODO Auto-generated GetDaysTest::setUp()
        $this->RW_View_Helper_GetDays = new RW_View_Helper_GetDays(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        // TODO Auto-generated GetDaysTest::tearDown()
        $this->RW_View_Helper_GetDays = null;
        parent::tearDown();
    }

    /**
     * Tests RW_View_Helper_GetDays->getDays()
     */
    public function testGetDays()
    {
        // TODO Auto-generated GetDaysTest->testGetDays()
        //$this->markTestIncomplete("getDays test not implemented");

        //Data de hoje
        $hoje = date('Y-m-d');
        self::assertEquals('hoje', $this->RW_View_Helper_GetDays->getDays($hoje));

        //Data de Ontem
        $ontem = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
        self::assertEquals('ontem', $this->RW_View_Helper_GetDays->getDays($ontem));

        //Data há 3 dias
        $dias = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')));
        self::assertEquals('há 3 dias', $this->RW_View_Helper_GetDays->getDays($dias));

        //Data há 2 semanas
        $semana = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 14, date('Y')));
        self::assertEquals('há 2 semanas', $this->RW_View_Helper_GetDays->getDays($semana));

        //Data há 1 Mês
        $mes = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 30, date('Y')));
        self::assertEquals('há 1 mês', $this->RW_View_Helper_GetDays->getDays($mes));

        //Data há 2 meses
        $meses = date('Y-m-d', mktime(0, 0, 0, date('m') - 2, date('d'), date('Y')));
        self::assertEquals('há 2 meses', $this->RW_View_Helper_GetDays->getDays($meses));

        //Data anos
        $ano = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y') - 1));
        $this->RW_View_Helper_GetDays->getDays($ano);
        //die($time);
    }
}

