<?php
/**
 * RW_Date test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class DateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_Date
     */
    private $RW_Date;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated DateTest::setUp()
        $this->RW_Date = new RW_Date(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated DateTest::tearDown()
        $this->RW_Date = null;
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
     * Tests RW_Date->toString()
     */
    public function testToString ()
    {
		$date = new RW_Date('27/12/2011','dd/MM/yyyy 00:00:00');
		$this->assertTrue($date->toString('mysql') === '2011-12-27 00:00:00');
    }


    /**
     * Tests RW_Date::toMySQL()
     */
    public function testToMySQL ()
    {
        $date = new RW_Date('26/12/2011 14:00:00');
        $this->assertEquals(RW_Date::toMySQL($date),'2011-12-26 14:00:00');

        $date = '26/12/2011 14:00:00';
        $this->assertEquals(RW_Date::toMySQL($date),'2011-12-26 14:00:00');

        $date = ('10/09/2012 14:00:00');
        $this->assertEquals(RW_Date::toMySQL($date),'2012-09-10 14:00:00');

        $this->assertNull(RW_Date::toMySQL(null));
        $this->assertNull(RW_Date::toMySQL(0));
        $this->assertNull(RW_Date::toMySQL(''));
        $this->assertNull(RW_Date::toMySQL(array()));
    }

    /**
     * Tests RW_Date::diff()
     */
    public function testDiff ()
    {
        $DATE_FORMAT = 'dd/MM/yyyy HH:mm:ss';

    	$date1 = new RW_Date('27/12/2011 14:00:00', $DATE_FORMAT);

    	//setando segundos por padrão
    	$date2 = new RW_Date('27/12/2011 14:00:27', $DATE_FORMAT);
		$this->assertTrue(RW_Date::diff($date2, $date1) === 27);


    	//segundos
    	$date2 = new RW_Date('27/12/2011 14:00:27', $DATE_FORMAT);
		$this->assertTrue(RW_Date::diff($date2, $date1, 's') === 27);

		//minutos
		$date2 = new RW_Date('27/12/2011 14:13:30', $DATE_FORMAT);
		$this->assertEquals(13.0, RW_Date::diff($date2, $date1, 'n'));

		//horas
		$date2 = new RW_Date('27/12/2011 15:00:00', $DATE_FORMAT);
		$this->assertEquals(1.0,RW_Date::diff($date2, $date1, 'h'));

		//dias
		$date2 = new RW_Date('30/12/2011 14:00:00', $DATE_FORMAT);
		$this->assertEquals(3.0,RW_Date::diff($date2, $date1, 'd'));

		//semanas
		$date2 = new RW_Date('10/01/2012 14:00:00', $DATE_FORMAT);
		$this->assertEquals(2.0,RW_Date::diff($date2, $date1, 'w'));

		//meses
		$date2 = new RW_Date('27/02/2012 14:00:00', $DATE_FORMAT);
		$this->assertEquals(2.0,RW_Date::diff($date2, $date1, 'm'));

		//anos
		$date2 = new RW_Date('27/12/2012 14:00:00', $DATE_FORMAT);
		$this->assertEquals(1.0,RW_Date::diff($date2, $date1, 'a'));
    }

    /**
     * Tests RW_Date->get()
     *
     * Testando apenas o trimestre
     */
    public function testGet ()
    {
    	$date = new RW_Date('01/01/2010', 'dd/MM/yyyy');

    	// 1o Trimestre
    	$date->setMonth(1);
    	$this->assertEquals(1,$date->get('Q'), 'Jan => 1o T');
    	$date->setMonth(2);
    	$this->assertEquals(1,$date->get('Q'), 'Fev => 1o T');
    	$date->setMonth(3);
    	$this->assertEquals(1,$date->get('Q'), 'Mar => 1o T');

    	// 2o Trimestre
    	$date->setMonth(4);
    	$this->assertEquals(2,$date->get('Q'), 'Abr => 2o T');
    	$date->setMonth(5);
    	$this->assertEquals(2,$date->get('Q'), 'Mai => 2o T');
    	$date->setMonth(6);
    	$this->assertEquals(2,$date->get('Q'), 'Jun => 2o T');

    	// 3o Trimestre
    	$date->setMonth(7);
    	$this->assertEquals(3,$date->get('Q'), 'Jul => 3o T');
    	$date->setMonth(8);
    	$this->assertEquals(3,$date->get('Q'), 'Ago => 3o T');
    	$date->setMonth(9);
    	$this->assertEquals(3,$date->get('Q'), 'Set => 3o T');

    	// 4o Trimestre
    	$date->setMonth(10);
    	$this->assertEquals(4,$date->get('Q'), 'Out => 4o T');
    	$date->setMonth(11);
    	$this->assertEquals(4,$date->get('Q'), 'Nov => 4o T');
    	$date->setMonth(12);
    	$this->assertEquals(4,$date->get('Q'), 'Dez => 4o T');
    }

    /**
     * Tests RW_Date->testGetMeses()
     */
    public function testGetMeses()
    {
    	$meses = $this->RW_Date->getMeses();

    	$this->assertEquals($meses,
	    	array(
	            1  => 'Janeiro',
	            2  => 'Fevereiro',
	            3  => 'Março',
	            4  => 'Abril',
	            5  => 'Maio',
	            6  => 'Junho',
	            7  => 'Julho',
	            8  => 'Agosto',
	            9  => 'Setembro',
	            10 => 'Outubro',
	            11 => 'Novembro',
	            12 => 'Dezembro'
	        ));
    }

    /**
     * Tests RW_Date->testGetMes()
     */
    public function testGetMes()
    {
    	$meses = $this->RW_Date->getMeses();

    	foreach ($meses as $m=>$mes) {
    	    $this->assertEquals($mes, $this->RW_Date->getMes($m), "getMes($m)");
    	}
    }

    public function testGetSemana()
    {
    	$data = '10/09/2012';
    	$this->assertEquals($this->RW_Date->getSemana($data), 'segunda');
    }


    public function testGetData()
    {
        $this->assertEquals($this->RW_Date->getData(''), null);
        $this->assertEquals($this->RW_Date->getData(null), null);
    }

    public function testConvertZendDateToDateTime()
    {
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('MM/dd/yyyy'), 'm/d/Y');
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('dd/MM/yyyy'), 'd/m/Y');
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('ddMMyyyy'),   'dmY');
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('dd\MM\yyyy'), 'd\m\Y');
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('yyyy/MM/dd'), 'Y/m/d');
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('yyyy-MM-dd'), 'Y-m-d');

        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('MM/dd/yyyy HH:mm:ss'), 'm/d/Y h:i:s');
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('dd/MM/yyyy HH:mm:ss'), 'd/m/Y h:i:s');
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('ddMMyyyy HH:mm:ss'),   'dmY h:i:s');
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('ddMMyyyy HHmmss'),   'dmY his');
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('dd\MM\yyyy HH:mm:ss'), 'd\m\Y h:i:s');
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('yyyy/MM/dd HH:mm:ss'), 'Y/m/d h:i:s');
        $this->assertEquals($this->RW_Date->convertZendDateToDateTime('yyyy-MM-dd HH:mm:ss'), 'Y-m-d h:i:s');

    }

    public function testIsDate()
    {
        $data = '10/09/2012';
        $this->assertTrue($this->RW_Date->isDate($data, 'dd/MM/yyyy'));
        $this->assertFalse($this->RW_Date->isDate($data, 'ddMMyyyy'));

        $data = '10092012';
        $this->assertFalse($this->RW_Date->isDate($data, 'dd/MM/yyyy'));
        $this->assertTrue($this->RW_Date->isDate($data, 'ddMMyyyy'));

        $data = '10092012 00:00:00';
        $this->assertFalse($this->RW_Date->isDate($data, 'dd/MM/yyyy HH:mm:ss'));
        $this->assertTrue($this->RW_Date->isDate($data, 'ddMMyyyy HH:mm:ss'));

        $data = '10092012 000000';
        $this->assertFalse($this->RW_Date->isDate($data, 'dd/MM/yyyy HHmmss'));
        $this->assertTrue($this->RW_Date->isDate($data, 'ddMMyyyy HHmmss'));

    }
}

