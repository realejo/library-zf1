<?php

namespace RWTest\View\Helper;

use RW_View_Helper_PrintDate;

/**
 * RW_View_Helper_PrintDate test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class PrintDateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RW_View_Helper_PrintDate
     */
    private $RW_View_Helper_PrintDate;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // TODO Auto-generated PrintDateTest::setUp()
        $this->RW_View_Helper_PrintDate = new RW_View_Helper_PrintDate(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        // TODO Auto-generated PrintDateTest::tearDown()
        $this->RW_View_Helper_PrintDate = null;
        parent::tearDown();
    }

    /**
     * Tests RW_View_Helper_PrintDate->printDate()
     */
    public function testPrintDate()
    {
        $dataCompleto = '2011-12-29 18:10:00';
        $data = '2011-12-29';
        $dataBarra = '29/12/2011';
        $dataHiphen = '2011-12-29';

        self::assertEquals('29/12/2011 00:00:00', $this->RW_View_Helper_PrintDate->printDate($dataHiphen, 'complete'));
        self::assertEquals('29/12/2011 00:00:00', $this->RW_View_Helper_PrintDate->printDate($dataBarra, 'complete'));
        self::assertEquals(
            '29/12/2011 18:10:00',
            $this->RW_View_Helper_PrintDate->printDate($dataCompleto, 'complete')
        );
        self::assertEquals('29/12/2011', $this->RW_View_Helper_PrintDate->printDate($data));
        self::assertEquals('29/12/2011', $this->RW_View_Helper_PrintDate->printDate($dataBarra));
        self::assertEquals($this->RW_View_Helper_PrintDate->printDate(), '');
    }
}

