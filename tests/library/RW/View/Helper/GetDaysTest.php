<?php

namespace RWTest\View\Helper;

use PHPUnit\Framework\TestCase;
use RW_View_Helper_GetDays;

class GetDaysTest extends TestCase
{
    private RW_View_Helper_GetDays $RW_View_Helper_GetDays;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->RW_View_Helper_GetDays = new RW_View_Helper_GetDays(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        unset($this->RW_View_Helper_GetDays);
        parent::tearDown();
    }

    public function testGetDays(): void
    {
        // TODO Auto-generated GetDaysTest->testGetDays()
        //$this->markTestIncomplete("getDays test not implemented");

        //Data de hoje
        $hoje = date('Y-m-d');
        self::assertEquals('hoje', $this->RW_View_Helper_GetDays->getDays($hoje));

        //Data de Ontem
        $ontem = date('Y-m-d', strtotime('1 day ago'));
        self::assertEquals('ontem', $this->RW_View_Helper_GetDays->getDays($ontem));

        //Data há 3 dias
        $dias = date('Y-m-d', strtotime('3 days ago'));
        self::assertEquals('há 3 dias', $this->RW_View_Helper_GetDays->getDays($dias));

        //Data há 2 semanas
        $semana = date('Y-m-d', strtotime('14 days ago'));
        self::assertEquals('há 2 semanas', $this->RW_View_Helper_GetDays->getDays($semana));

        //Data há 1 Mês
        $mes = date('Y-m-d', strtotime('30 days ago'));
        self::assertEquals('há 1 mês', $this->RW_View_Helper_GetDays->getDays($mes));

        //Data há 2 meses
        $meses = date('Y-m-d', strtotime('2 months ago'));
        self::assertEquals('há 1 mês', $this->RW_View_Helper_GetDays->getDays($meses));

        //Data há 2 meses
        $meses = date('Y-m-d', strtotime('70 days ago'));
        self::assertEquals('há 2 meses', $this->RW_View_Helper_GetDays->getDays($meses));

        //Data anos
        $ano = date('Y-m-d', strtotime('1 year ago'));
        $this->RW_View_Helper_GetDays->getDays($ano);
    }
}

