<?php
require_once 'RW/View/Helper/GetDays.php';
/**
 * RW_View_Helper_GetDays test case.
 */
class GetDaysTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_View_Helper_GetDays
     */
    private $RW_View_Helper_GetDays;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated GetDaysTest::setUp()
        $this->RW_View_Helper_GetDays = new RW_View_Helper_GetDays(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated GetDaysTest::tearDown()
        $this->RW_View_Helper_GetDays = null;
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
     * Tests RW_View_Helper_GetDays->getDays()
     */
    public function testGetDays ()
    {
        // TODO Auto-generated GetDaysTest->testGetDays()
        //$this->markTestIncomplete("getDays test not implemented");

    	//Data de hoje
    	$hoje = date('Y-m-d');
    	$this->assertEquals('hoje', $this->RW_View_Helper_GetDays->getDays($hoje));
    	
    	//Data de Ontem
        $ontem = date('Y-m-d',mktime(0,0,0,date('m'),date('d') - 1,date('Y')));
    	$this->assertEquals('ontem', $this->RW_View_Helper_GetDays->getDays($ontem));
        
        //Data há 3 dias
        $dias = date('Y-m-d',mktime(0,0,0,date('m'),date('d') - 2,date('Y')));
    	$this->assertEquals('há 3 dias', $this->RW_View_Helper_GetDays->getDays($dias));

        //Data há 2 semanas
        $semana = date('Y-m-d',mktime(0,0,0,date('m'),date('d') - 14,date('Y')));
    	$this->assertEquals('há 2 semanas', $this->RW_View_Helper_GetDays->getDays($semana));

        //Data há 1 Mês
        $mes = date('Y-m-d',mktime(0,0,0,date('m'),date('d') - 30,date('Y')));         
    	$this->assertEquals('há 1 mês', $this->RW_View_Helper_GetDays->getDays($mes));              
        
        //Data há 2 meses
        $meses = date('Y-m-d',mktime(0,0,0,date('m') - 2,date('d'),date('Y'))); 
    	$this->assertEquals('há 2 meses', $this->RW_View_Helper_GetDays->getDays($meses));   

        //Data anos
        $ano = date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y')-1));
        $this->RW_View_Helper_GetDays->getDays($ano);
         	
        
        //die($time);
    }
}

