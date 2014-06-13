<?php
/**
 * RW_View_Helper_PrintDate test case.
 *
 * @link      http://github.com/realejo/library-zf1
 * @copyright Copyright (c) 2011-2014 Realejo Design Ltda. (http://www.realejo.com.br)
 */
require_once 'RW/View/Helper/PrintDate.php';

class PrintDateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_View_Helper_PrintDate
     */
    private $RW_View_Helper_PrintDate;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated PrintDateTest::setUp()
        $this->RW_View_Helper_PrintDate = new RW_View_Helper_PrintDate(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated PrintDateTest::tearDown()
        $this->RW_View_Helper_PrintDate = null;
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
     * Tests RW_View_Helper_PrintDate->printDate()
     */
    public function testPrintDate ()
    {
    	$dataCompleto = '2011-12-29 18:10:00';
    	$data 		  = '2011-12-29';
    	$dataBarra 	  = '29/12/2011';
    	$dataHiphen   = '2011-12-29';

    	$this->assertEquals('29/12/2011 00:00:00', $this->RW_View_Helper_PrintDate->printDate($dataHiphen , 'complete'));
    	$this->assertEquals('29/12/2011 00:00:00', $this->RW_View_Helper_PrintDate->printDate($dataBarra , 'complete'));
        $this->assertEquals('29/12/2011 18:10:00', $this->RW_View_Helper_PrintDate->printDate($dataCompleto , 'complete'));
        $this->assertEquals('29/12/2011', $this->RW_View_Helper_PrintDate->printDate($data));
        $this->assertEquals('29/12/2011', $this->RW_View_Helper_PrintDate->printDate($dataBarra));
        $this->assertEquals($this->RW_View_Helper_PrintDate->printDate(), '');
    }
}

