<?php
/**
 * RW_Date test case.
 *
 * @category   RW
 * @package    RW_Date
 * @subpackage UnitTests
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */

require_once 'RW/Date.php';

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
      $this->assertTrue(RW_Date::toMySQL($date) === '2011-12-26 14:00:00');

      $date = '26/12/2011 14:00:00';
      $this->assertTrue(RW_Date::toMySQL($date) === '2011-12-26 14:00:00');


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
     */
    public function testGet ()
    {
    	$date = new RW_Date('10/09/2012 14:00:00', 'dd/MM/yyyy HH:mm:ss');
    	$this->assertEquals(3.0,$date->get('Q'));
    }





}

