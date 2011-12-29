<?php
require_once 'RW/View/Helper/GetTime.php';
/**
 * RW_View_Helper_GetTime test case.
 */
class GetTimeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_View_Helper_GetTime
     */
    private $RW_View_Helper_GetTime;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated GetTimeTest::setUp()
        $this->RW_View_Helper_GetTime = new RW_View_Helper_GetTime(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated GetTimeTest::tearDown()
        $this->RW_View_Helper_GetTime = null;
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
     * Tests RW_View_Helper_GetTime->getTime()
     */
    public function testGetTime ()
    {
        // TODO Auto-generated GetTimeTest->testGetTime()
        //$this->markTestIncomplete("getTime test not implemented");
        
        //Diferença de 10 segundos
    	$segundos = date('Y-m-d H:i:s',mktime(date('H'),date('i'),date('s')-10,date('m'),date('d'),date('Y')));
    	$this->assertEquals('10 segundos', $this->RW_View_Helper_GetTime->getTime($segundos));
        
    	//Diferença de 2 minutos
    	$minutos = date('Y-m-d H:i:s',mktime(date('H'),date('i')-2,date('s'),date('m'),date('d'),date('Y')));
    	$this->assertEquals('2 minutos', $this->RW_View_Helper_GetTime->getTime($minutos));    	
    	
    	//Diferença de 2 horas
    	$minutos = date('Y-m-d H:i:s',mktime(date('H')-2,date('i'),date('s'),date('m'),date('d'),date('Y')));
    	$this->assertEquals('2 horas', $this->RW_View_Helper_GetTime->getTime($minutos));      	
    	
    	//Diferença de 2 dias
    	$minutos = date('Y-m-d H:i:s',mktime(date('H')-48,date('i'),date('s'),date('m'),date('d'),date('Y')));
    	$this->assertEquals('2 dias', $this->RW_View_Helper_GetTime->getTime($minutos));    	    	
    	
     	//Diferença de 2 dias
    	$semanas = date('Y-m-d H:i:s',mktime(date('H'),date('i'),date('s'),date('m'),date('d')-16,date('Y')));
    	$this->assertEquals('2 semanas', $this->RW_View_Helper_GetTime->getTime($semanas));   

        //Data há 1 Mês
        $mes = date('Y-m-d',mktime(date('H'),date('i'),date('s'),date('m'),date('d') - 30,date('Y')));         
    	$this->assertEquals('1 mês', $this->RW_View_Helper_GetTime->getTime($mes));              
        
        //Data há 2 meses
        $meses = date('Y-m-d',mktime(date('H'),date('i'),date('s'),date('m') - 2,date('d'),date('Y'))); 
    	$this->assertEquals('2 meses', $this->RW_View_Helper_GetTime->getTime($meses));   

        //Data anos
        $ano = date('Y-m-d',mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')-1));
        $this->RW_View_Helper_GetTime->getTime($ano);   	

    }
}

