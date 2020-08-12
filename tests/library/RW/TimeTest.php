<?php

namespace RWTest;

use PHPUnit\Framework\TestCase;
use RW_Time;
use Zend_Date;

/**
 * RW_Time test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class TimeTest extends TestCase
{
    /**
     * @var RW_Time
     */
    private $RW_Time;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->RW_Time = new RW_Time(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->RW_Time = null;
        parent::tearDown();
    }

    /**
     * Tests RW_Math::moda()
     */
    public function testGet()
    {
        /**
         * SEGUNDOS
         */
        // 1 segundo
        $time = new RW_Time(1);
        self::assertSame('01', $time->get(RW_Time::SECOND));
        self::assertSame('1', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('00', $time->get(RW_Time::MINUTE));
        self::assertSame('0', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));

        // 1 segundo
        $time = new RW_Time(1, RW_Time::SECOND);
        self::assertSame('01', $time->get(RW_Time::SECOND));
        self::assertSame('1', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('00', $time->get(RW_Time::MINUTE));
        self::assertSame('0', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));


        // 13 segundos
        $time = new RW_Time(13);
        self::assertSame('13', $time->get(RW_Time::SECOND));
        self::assertSame('13', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('00', $time->get(RW_Time::MINUTE));
        self::assertSame('0', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));

        // 13 segundos
        $time = new RW_Time(13, RW_Time::SECOND);
        self::assertSame('13', $time->get(RW_Time::SECOND));
        self::assertSame('13', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('00', $time->get(RW_Time::MINUTE));
        self::assertSame('0', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));


        // 63 segundos
        $time = new RW_Time(63);
        self::assertSame('03', $time->get(RW_Time::SECOND));
        self::assertSame('3', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('01', $time->get(RW_Time::MINUTE));
        self::assertSame('1', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));

        // 63 segundos
        $time = new RW_Time(63, RW_Time::SECOND);
        self::assertSame('03', $time->get(RW_Time::SECOND));
        self::assertSame('3', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('01', $time->get(RW_Time::MINUTE));
        self::assertSame('1', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));


        /**
         * MINUTOS
         */

        // 1 minuto
        $time = new RW_Time(60);
        self::assertSame('00', $time->get(RW_Time::SECOND));
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('01', $time->get(RW_Time::MINUTE));
        self::assertSame('1', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));

        // 1 minuto
        $time = new RW_Time(1, RW_Time::MINUTE);
        self::assertSame('00', $time->get(RW_Time::SECOND));
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('01', $time->get(RW_Time::MINUTE));
        self::assertSame('1', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));


        // 13 minutos
        $time = new RW_Time(13 * 60);
        self::assertSame('00', $time->get(RW_Time::SECOND));
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('13', $time->get(RW_Time::MINUTE));
        self::assertSame('13', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));

        // 13 minutos
        $time = new RW_Time(13, RW_Time::MINUTE);
        self::assertSame('00', $time->get(RW_Time::SECOND));
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('13', $time->get(RW_Time::MINUTE));
        self::assertSame('13', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));


        // 13,5 minutos
        $time = new RW_Time(13 * 60 + 30);
        self::assertSame('30', $time->get(RW_Time::SECOND));
        self::assertSame('30', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('13', $time->get(RW_Time::MINUTE));
        self::assertSame('13', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));

        // 13,5 minutos
        $time = new RW_Time(13.5, RW_Time::MINUTE);
        self::assertSame('30', $time->get(RW_Time::SECOND));
        self::assertSame('30', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('13', $time->get(RW_Time::MINUTE));
        self::assertSame('13', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));

        // 13:30 minutos
        $time = new RW_Time('13:30', RW_Time::MINUTE . ':' . RW_Time::SECOND);
        self::assertSame('30', $time->get(RW_Time::SECOND));
        self::assertSame('30', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('13', $time->get(RW_Time::MINUTE));
        self::assertSame('13', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('00', $time->get(RW_Time::HOUR));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));

        /**
         * HORAS
         */

        // 1 hora
        $time = new RW_Time(60 * 60);
        self::assertSame('00', $time->get(RW_Time::SECOND));
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('00', $time->get(RW_Time::MINUTE));
        self::assertSame('0', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('01', $time->get(RW_Time::HOUR));
        self::assertSame('1', $time->get(RW_Time::HOUR_SHORT));

        // 1 hora
        $time = new RW_Time(1, RW_Time::HOUR);
        self::assertSame('00', $time->get(RW_Time::SECOND));
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('00', $time->get(RW_Time::MINUTE));
        self::assertSame('0', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('01', $time->get(RW_Time::HOUR));
        self::assertSame('1', $time->get(RW_Time::HOUR_SHORT));


        // 120 horas
        $time = new RW_Time(120 * 60 * 60);
        self::assertSame('00', $time->get(RW_Time::SECOND));
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('00', $time->get(RW_Time::MINUTE));
        self::assertSame('0', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('120', $time->get(RW_Time::HOUR));
        self::assertSame('120', $time->get(RW_Time::HOUR_SHORT));

        // 120 horas
        $time = new RW_Time(120, RW_Time::HOUR);
        self::assertSame('00', $time->get(RW_Time::SECOND));
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('00', $time->get(RW_Time::MINUTE));
        self::assertSame('0', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('120', $time->get(RW_Time::HOUR));
        self::assertSame('120', $time->get(RW_Time::HOUR_SHORT));


        // 27 horas
        $time = new RW_Time(27 * 60 * 60);
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('0', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('27', $time->get(RW_Time::HOUR_SHORT));

        // 27 horas
        $time = new RW_Time(27, RW_Time::HOUR);
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('0', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('27', $time->get(RW_Time::HOUR_SHORT));

        // 27,5 horas
        $time = new RW_Time(27.5 * 60 * 60);
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('30', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('27', $time->get(RW_Time::HOUR_SHORT));

        // 27,5 horas
        $time = new RW_Time(27.5, RW_Time::HOUR);
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('30', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('27', $time->get(RW_Time::HOUR_SHORT));

        // 13:45:3
        $time = new RW_Time(13 * 60 * 60 + 45 * 60 + 3);
        self::assertSame('3', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('45', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('13', $time->get(RW_Time::HOUR_SHORT));

        // 13:45:03 minutos
        $time = new RW_Time('13:45:3', RW_Time::HOUR . ':' . RW_Time::MINUTE . ':' . RW_Time::SECOND);
        self::assertSame('3', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('45', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('13', $time->get(RW_Time::HOUR_SHORT));


        /**
         * FORMATOS
         */
        $time = new RW_Time('13:24', RW_Time::HOUR . ':' . RW_Time::MINUTE);
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('24', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('13', $time->get(RW_Time::HOUR_SHORT));

        $time = new RW_Time('13:24');
        self::assertSame('24', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('13', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));

        $time = new RW_Time('13:24:15');
        self::assertSame('15', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('24', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('13', $time->get(RW_Time::HOUR_SHORT));

        // hora inválida
        $time = new RW_Time('13:60:00');
        self::assertSame('0', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('0', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('14', $time->get(RW_Time::HOUR_SHORT));

        // formato teoricamnente inválido
        $time = new RW_Time('13:24', RW_Time::SECOND . ':' . RW_Time::MINUTE);
        self::assertSame('13', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('24', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('0', $time->get(RW_Time::HOUR_SHORT));

        /**
         * Zend_Date
         */
        $time = new RW_Time(new Zend_Date('12/01/2012 13:24:45', 'dd/MM/yyyy hh:mm:ss'));
        self::assertSame('45', $time->get(RW_Time::SECOND_SHORT));
        self::assertSame('24', $time->get(RW_Time::MINUTE_SHORT));
        self::assertSame('13', $time->get(RW_Time::HOUR_SHORT));
    }

    /**
     * Tests RW_Time::toString()
     */
    public function testToString()
    {
        $time = new RW_Time(49503); // 13:45:3,
        self::assertSame('13:45:03', $time->toString());
        self::assertSame('13:45:03', $time->toString('hh:mm:ss'));
        self::assertSame('13:45:3', $time->toString('h:m:s'));
        self::assertSame('3', $time->toString('s'));
        self::assertSame('03', $time->toString('ss'));
        self::assertSame('45', $time->toString('m'));
        self::assertSame('13', $time->toString('h'));
        self::assertSame('=> 13', $time->toString('=> h'));

        self::assertSame('13:45:03', $time->toString());
        self::assertSame('13:45:03', $time->toString('Shh:mm:ss'));
        self::assertSame('13:45:3', $time->toString('Sh:m:s'));
        self::assertSame('3', $time->toString('Ss'));
        self::assertSame('03', $time->toString('Sss'));
        self::assertSame('45', $time->toString('Sm'));
        self::assertSame('13', $time->toString('Sh'));
        self::assertSame('=> 13', $time->toString('=> Sh'));

        $time = new RW_Time(-49503); // 13:45:3,
        self::assertSame('-13:45:03', $time->toString());
        self::assertSame('13:45:03', $time->toString('hh:mm:ss'));
        self::assertSame('13:45:3', $time->toString('h:m:s'));
        self::assertSame('3', $time->toString('s'));
        self::assertSame('03', $time->toString('ss'));
        self::assertSame('45', $time->toString('m'));
        self::assertSame('13', $time->toString('h'));
        self::assertSame('=> 13', $time->toString('=> h'));
        self::assertSame('-', $time->toString('S'));

        self::assertSame('-13:45:03', $time->toString('Shh:mm:ss'));
        self::assertSame('-13:45:3', $time->toString('Sh:m:s'));
        self::assertSame('-3', $time->toString('Ss'));
        self::assertSame('-03', $time->toString('Sss'));
        self::assertSame('-45', $time->toString('Sm'));
        self::assertSame('-13', $time->toString('Sh'));
        self::assertSame('=> -13', $time->toString('=> Sh'));
    }


    /**
     * Tests RW_Time::setSeconds()
     */
    public function testSetSeconds()
    {
        $time = new RW_Time(3);
        self::assertSame('00:00:27', $time->setSeconds(27)->toString());
        self::assertSame('00:01:13', $time->setSeconds(73)->toString());

        $time = new RW_Time('27:3');
        self::assertSame('00:27:27', $time->setSeconds(27)->toString());
        self::assertSame('00:28:13', $time->setSeconds(73)->toString());

        $time = new RW_Time('13:27:3');
        self::assertSame('13:27:27', $time->setSeconds(27)->toString());
        self::assertSame('13:28:13', $time->setSeconds(73)->toString());
    }


    /**
     * Tests RW_Time::setMinutes()
     */
    public function testSetMinutes()
    {
        $time = new RW_Time(61);
        self::assertSame('00:27:01', $time->setMinutes(27)->toString());
        self::assertSame('01:13:01', $time->setMinutes(73)->toString());

        $time = new RW_Time('27:3');
        self::assertSame('00:32:03', $time->setMinutes(32)->toString());
        self::assertSame('01:13:03', $time->setMinutes(73)->toString());

        $time = new RW_Time('13:27:3');
        self::assertSame('13:27:03', $time->setMinutes(27)->toString());
        self::assertSame('14:13:03', $time->setMinutes(73)->toString());
    }


    /**
     * Tests RW_Time::setHours()
     */
    public function testSetHours()
    {
        $time = new RW_Time(61);
        self::assertSame('27:01:01', $time->setHours(27)->toString());

        $time = new RW_Time('27:3');
        self::assertSame('32:27:03', $time->setHours(32)->toString());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame('32:03:00', $time->setHours(32)->toString());

        $time = new RW_Time('13:27:3');
        self::assertSame('32:27:03', $time->setHours(32)->toString());
    }

    public function testSetTimeException()
    {
        $this->expectException(\Exception::class);
        $this->RW_Time->setTime('abcde');
    }

    /**
     * Tests RW_Time::getSeconds()
     */
    public function testGetSeconds()
    {
        $time = new RW_Time(61);
        self::assertSame(61, $time->getSeconds());

        $time = new RW_Time('27:3');
        self::assertSame(27 * 60 + 3, $time->getSeconds());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame(27 * 60 * 60 + 3 * 60, $time->getSeconds());

        $time = new RW_Time('13:27:3');
        self::assertSame(13 * 60 * 60 + 27 * 60 + 3, $time->getSeconds());

        $time = new RW_Time(61, RW_Time::MINUTE);
        self::assertSame(61 * 60, $time->getSeconds());

        $time = new RW_Time(61, RW_Time::HOUR);
        self::assertSame(61 * 60 * 60, $time->getSeconds());

        $time = new RW_Time(13, RW_Time::MINUTE);
        self::assertSame(13 * 60, $time->getSeconds());

        $time = new RW_Time(13, RW_Time::HOUR);
        self::assertSame(13 * 60 * 60, $time->getSeconds());
    }

    /**
     * Tests RW_Time::getMinutes()
     */
    public function testGetMinutes()
    {
        $time = new RW_Time(61);
        self::assertSame(61 / 60, $time->getMinutes());

        $time = new RW_Time('27:3');
        self::assertSame((27 * 60 + 3) / 60, $time->getMinutes());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame((27 * 60 * 60 + 3 * 60) / 60, $time->getMinutes());

        $time = new RW_Time('13:27:3');
        self::assertSame((13 * 60 * 60 + 27 * 60 + 3) / 60, $time->getMinutes());

        $time = new RW_Time(61, RW_Time::MINUTE);
        self::assertSame(61, $time->getMinutes());

        $time = new RW_Time(61, RW_Time::HOUR);
        self::assertSame(61 * 60, $time->getMinutes());
    }

    /**
     * Tests RW_Time::getHours()
     */
    public function testGetHours()
    {
        $time = new RW_Time(61);
        self::assertSame(61 / 3600, $time->getHours());

        $time = new RW_Time('27:3');
        self::assertSame((27 * 60 + 3) / 3600, $time->getHours());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame((27 * 60 * 60 + 3 * 60) / 3600, $time->getHours());

        $time = new RW_Time('13:27:3');
        self::assertSame((13 * 60 * 60 + 27 * 60 + 3) / 3600, $time->getHours());
    }

    /**
     * Tests RW_Time::addSeconds()
     */
    public function testAddSeconds()
    {
        $time = new RW_Time(61);
        self::assertSame(61 + 13, $time->addSeconds(13)->getSeconds());

        $time = new RW_Time('27:3');
        self::assertSame(27 * 60 + 3 + 13, $time->addSeconds(13)->getSeconds());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame(27 * 60 * 60 + 3 * 60 + 13, $time->addSeconds(13)->getSeconds());

        $time = new RW_Time('13:27:3');
        self::assertSame(13 * 60 * 60 + 27 * 60 + 3 + 13, $time->addSeconds(13)->getSeconds());
    }

    /**
     * Tests RW_Time::subSeconds()
     */
    public function testSubSeconds()
    {
        $time = new RW_Time(61);
        self::assertSame(61 - 13, $time->subSeconds(13)->getSeconds());

        $time = new RW_Time('27:3');
        self::assertSame(27 * 60 + 3 - 13, $time->subSeconds(13)->getSeconds());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame(27 * 60 * 60 + 3 * 60 - 13, $time->subSeconds(13)->getSeconds());

        $time = new RW_Time('13:27:3');
        self::assertSame(13 * 60 * 60 + 27 * 60 + 3 - 13, $time->subSeconds(13)->getSeconds());
    }

    /**
     * Tests RW_Time::addMinutes()
     */
    public function testAddMinutes()
    {
        $time = new RW_Time(61);
        self::assertSame(61 + 13 * 60, $time->addMinutes(13)->getSeconds());

        $time = new RW_Time('27:3');
        self::assertSame(27 * 60 + 3 + 13 * 60, $time->addMinutes(13)->getSeconds());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame(27 * 60 * 60 + 3 * 60 + 13 * 60, $time->addMinutes(13)->getSeconds());

        $time = new RW_Time('13:27:3');
        self::assertSame(13 * 60 * 60 + 27 * 60 + 3 + 13 * 60, $time->addMinutes(13)->getSeconds());
    }

    /**
     * Tests RW_Time::subMinutes()
     */
    public function testSubMinutes()
    {
        $time = new RW_Time(61);
        self::assertSame(61 - 13 * 60, $time->subMinutes(13)->getSeconds());

        $time = new RW_Time('27:3');
        self::assertSame(27 * 60 + 3 - 13 * 60, $time->subMinutes(13)->getSeconds());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame(27 * 60 * 60 + 3 * 60 - 13 * 60, $time->subMinutes(13)->getSeconds());

        $time = new RW_Time('13:27:3');
        self::assertSame(13 * 60 * 60 + 27 * 60 + 3 - 13 * 60, $time->subMinutes(13)->getSeconds());
    }

    /**
     * Tests RW_Time::addHours()
     */
    public function testAddHours()
    {
        $time = new RW_Time(61);
        self::assertSame(61 + 13 * 60 * 60, $time->addHours(13)->getSeconds());

        $time = new RW_Time('27:3');
        self::assertSame(27 * 60 + 3 + 13 * 60 * 60, $time->addHours(13)->getSeconds());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame(27 * 60 * 60 + 3 * 60 + 13 * 60 * 60, $time->addHours(13)->getSeconds());

        $time = new RW_Time('13:27:3');
        self::assertSame(13 * 60 * 60 + 27 * 60 + 3 + 13 * 60 * 60, $time->addHours(13)->getSeconds());
    }

    /**
     * Tests RW_Time::subHours()
     */
    public function testSubHours()
    {
        $time = new RW_Time(61);
        self::assertSame(61 - 13 * 60 * 60, $time->subHours(13)->getSeconds());

        $time = new RW_Time('27:3');
        self::assertSame(27 * 60 + 3 - 13 * 60 * 60, $time->subHours(13)->getSeconds());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame(27 * 60 * 60 + 3 * 60 - 13 * 60 * 60, $time->subHours(13)->getSeconds());

        $time = new RW_Time('13:27:3');
        self::assertSame(13 * 60 * 60 + 27 * 60 + 3 - 13 * 60 * 60, $time->subHours(13)->getSeconds());
    }

    /**
     * Tests RW_Time::addTime()
     */
    public function testAddTime()
    {
        $time = new RW_Time(61);
        self::assertSame(61 + 13, $time->setTime(61)->addTime(13)->getSeconds());
        self::assertSame(61 + 13 * 60, $time->setTime(61)->addTime(13, RW_Time::MINUTE)->getSeconds());
        self::assertSame(61 + 13 * 60, $time->setTime(61)->addTime(13, RW_Time::MINUTE)->getSeconds());
        self::assertSame(61 + 13 * 60, $time->setTime(61)->addTime('13:00')->getSeconds());
        self::assertSame(61 + 13 * 60 * 60, $time->setTime(61)->addTime(13, RW_Time::HOUR)->getSeconds());
        self::assertSame(61 + 13 * 60 * 60, $time->setTime(61)->addTime('13:00:00')->getSeconds());

        $time = new RW_Time('27:3');
        self::assertSame(27 * 60 + 3 + 13, $time->setTime('27:3')->addTime(13)->getSeconds());
        self::assertSame(27 * 60 + 3 + 13 * 60, $time->setTime('27:3')->addTime(13, RW_Time::MINUTE)->getSeconds());
        self::assertSame(27 * 60 + 3 + 13 * 60, $time->setTime('27:3')->addTime('13:00')->getSeconds());
        self::assertSame(27 * 60 + 3 + 13 * 60 * 60, $time->setTime('27:3')->addTime(13, RW_Time::HOUR)->getSeconds());
        self::assertSame(27 * 60 + 3 + 13 * 60 * 60, $time->setTime('27:3')->addTime('13:00:00')->getSeconds());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame(27 * 60 * 60 + 3 * 60 + 13, $time->setTime('27:3', 'h:m')->addTime(13)->getSeconds());
        self::assertSame(
            27 * 60 * 60 + 3 * 60 + 13 * 60,
            $time->setTime('27:3', 'h:m')->addTime(13, RW_Time::MINUTE)->getSeconds()
        );
        self::assertSame(
            27 * 60 * 60 + 3 * 60 + 13 * 60,
            $time->setTime('27:3', 'h:m')->addTime('13:00')->getSeconds()
        );
        self::assertSame(
            27 * 60 * 60 + 3 * 60 + 13 * 60 * 60,
            $time->setTime('27:3', 'h:m')->addTime(13, RW_Time::HOUR)->getSeconds()
        );
        self::assertSame(
            27 * 60 * 60 + 3 * 60 + 13 * 60 * 60,
            $time->setTime('27:3', 'h:m')->addTime('13:00:00')->getSeconds()
        );

        $time = new RW_Time('13:27:3');
        self::assertSame(13 * 60 * 60 + 27 * 60 + 3 + 13, $time->setTime('13:27:3')->addTime(13)->getSeconds());
        self::assertSame(
            13 * 60 * 60 + 27 * 60 + 3 + 13 * 60,
            $time->setTime('13:27:3')->addTime(13, RW_Time::MINUTE)->getSeconds()
        );
        self::assertSame(
            13 * 60 * 60 + 27 * 60 + 3 + 13 * 60,
            $time->setTime('13:27:3')->addTime('13:00')->getSeconds()
        );
        self::assertSame(
            13 * 60 * 60 + 27 * 60 + 3 + 13 * 60 * 60,
            $time->setTime('13:27:3')->addTime(13, RW_Time::HOUR)->getSeconds()
        );
        self::assertSame(
            13 * 60 * 60 + 27 * 60 + 3 + 13 * 60 * 60,
            $time->setTime('13:27:3')->addTime('13:00:00')->getSeconds()
        );
    }

    /**
     * Tests RW_Time::subTime()
     */
    public function testSubTime()
    {
        $time = new RW_Time(61);
        self::assertSame(61 - 13, $time->setTime(61)->subTime(13)->getSeconds());
        self::assertSame(61 - 13 * 60, $time->setTime(61)->subTime(13, RW_Time::MINUTE)->getSeconds());
        self::assertSame(61 - 13 * 60, $time->setTime(61)->subTime(13, RW_Time::MINUTE)->getSeconds());
        self::assertSame(61 - 13 * 60, $time->setTime(61)->subTime('13:00')->getSeconds());
        self::assertSame(61 - 13 * 60 * 60, $time->setTime(61)->subTime(13, RW_Time::HOUR)->getSeconds());
        self::assertSame(61 - 13 * 60 * 60, $time->setTime(61)->subTime('13:00:00')->getSeconds());

        $time = new RW_Time('27:3');
        self::assertSame(27 * 60 + 3 - 13, $time->setTime('27:3')->subTime(13)->getSeconds());
        self::assertSame(27 * 60 + 3 - 13 * 60, $time->setTime('27:3')->subTime(13, RW_Time::MINUTE)->getSeconds());
        self::assertSame(27 * 60 + 3 - 13 * 60, $time->setTime('27:3')->subTime('13:00')->getSeconds());
        self::assertSame(27 * 60 + 3 - 13 * 60 * 60, $time->setTime('27:3')->subTime(13, RW_Time::HOUR)->getSeconds());
        self::assertSame(27 * 60 + 3 - 13 * 60 * 60, $time->setTime('27:3')->subTime('13:00:00')->getSeconds());

        $time = new RW_Time('27:3', 'h:m');
        self::assertSame(27 * 60 * 60 + 3 * 60 - 13, $time->setTime('27:3', 'h:m')->subTime(13)->getSeconds());
        self::assertSame(
            27 * 60 * 60 + 3 * 60 - 13 * 60,
            $time->setTime('27:3', 'h:m')->subTime(13, RW_Time::MINUTE)->getSeconds()
        );
        self::assertSame(
            27 * 60 * 60 + 3 * 60 - 13 * 60,
            $time->setTime('27:3', 'h:m')->subTime('13:00')->getSeconds()
        );
        self::assertSame(
            27 * 60 * 60 + 3 * 60 - 13 * 60 * 60,
            $time->setTime('27:3', 'h:m')->subTime(13, RW_Time::HOUR)->getSeconds()
        );
        self::assertSame(
            27 * 60 * 60 + 3 * 60 - 13 * 60 * 60,
            $time->setTime('27:3', 'h:m')->subTime('13:00:00')->getSeconds()
        );

        $time = new RW_Time('13:27:3');
        self::assertSame(13 * 60 * 60 + 27 * 60 + 3 - 13, $time->setTime('13:27:3')->subTime(13)->getSeconds());
        self::assertSame(
            13 * 60 * 60 + 27 * 60 + 3 - 13 * 60,
            $time->setTime('13:27:3')->subTime(13, RW_Time::MINUTE)->getSeconds()
        );
        self::assertSame(
            13 * 60 * 60 + 27 * 60 + 3 - 13 * 60,
            $time->setTime('13:27:3')->subTime('13:00')->getSeconds()
        );
        self::assertSame(
            13 * 60 * 60 + 27 * 60 + 3 - 13 * 60 * 60,
            $time->setTime('13:27:3')->subTime(13, RW_Time::HOUR)->getSeconds()
        );
        self::assertSame(
            13 * 60 * 60 + 27 * 60 + 3 - 13 * 60 * 60,
            $time->setTime('13:27:3')->subTime('13:00:00')->getSeconds()
        );
    }

    /**
     * Tests RW_Time::isTime()
     */
    public function testIsTime()
    {
        self::assertTrue(RW_Time::isTime('13:27:30'));
        self::assertTrue(RW_Time::isTime('13:27:3'));
        self::assertTrue(RW_Time::isTime('13:2:3'));
        self::assertTrue(RW_Time::isTime('1:2:3'));
        self::assertTrue(RW_Time::isTime('27:3'));
        self::assertTrue(RW_Time::isTime('27:30'));
        self::assertTrue(RW_Time::isTime('3'));
        self::assertTrue(RW_Time::isTime(3));

        self::assertTrue(RW_Time::isTime('-13:27:30'));
        self::assertTrue(RW_Time::isTime('-13:27:3'));
        self::assertTrue(RW_Time::isTime('-13:2:3'));
        self::assertTrue(RW_Time::isTime('-1:2:3'));
        self::assertTrue(RW_Time::isTime('-27:3'));
        self::assertTrue(RW_Time::isTime('-27:30'));
        self::assertTrue(RW_Time::isTime('-3'));
        self::assertTrue(RW_Time::isTime(-3));

        self::assertFalse(RW_Time::isTime('13:27:3a'));
        self::assertFalse(RW_Time::isTime('13:27a:3'));
        self::assertFalse(RW_Time::isTime('13a:27:3'));
        self::assertFalse(RW_Time::isTime('13a:2:3'));
        self::assertFalse(RW_Time::isTime('13a:2:3'));
        self::assertFalse(RW_Time::isTime('13:33:33:33'));
        self::assertFalse(RW_Time::isTime(':27:3'));
        self::assertFalse(RW_Time::isTime('13:27:'));
        self::assertFalse(RW_Time::isTime('13::3'));

        self::assertFalse(RW_Time::isTime('-13:27:3a'));
        self::assertFalse(RW_Time::isTime('-13:27a:3'));
        self::assertFalse(RW_Time::isTime('-13a:27:3'));
        self::assertFalse(RW_Time::isTime('-13a:2:3'));
        self::assertFalse(RW_Time::isTime('-13a:2:3'));
        self::assertFalse(RW_Time::isTime('-13:33:33:33'));
        self::assertFalse(RW_Time::isTime('-:27:3'));
        self::assertFalse(RW_Time::isTime('-13:27:'));
        self::assertFalse(RW_Time::isTime('-13::3'));

        self::assertFalse(RW_Time::isTime('--13:27:30'));
        self::assertFalse(RW_Time::isTime('--13:27:3'));
        self::assertFalse(RW_Time::isTime('--13:2:3'));
        self::assertFalse(RW_Time::isTime('--1:2:3'));
        self::assertFalse(RW_Time::isTime('--27:3'));
        self::assertFalse(RW_Time::isTime('--27:30'));
        self::assertFalse(RW_Time::isTime('--3'));
    }

    public function testLongSeconds()
    {
        $time = new RW_Time(390526);
        self::assertSame('108:28:46', $time->toString());
        self::assertSame(390526, $time->getSeconds());
    }
}
