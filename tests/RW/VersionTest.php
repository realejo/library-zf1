<?php
/**
 * RW_Version test case.
 *
 * @category   RW
 * @package    RW_Version
 * @subpackage UnitTests
 * @author     Realejo
 * @version    $Id: VersionTest.php 7 2012-01-11 17:15:57Z rodrigo $
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */
require_once 'RW/Version.php';

class VersionTest extends PHPUnit_Framework_TestCase
{
    public function testGetLatest()
    {
        $this->assertNotEmpty(RW_Version::getLatest());
    }

    public function testCompareVersion()
    {
        $this->assertEquals(0, RW_Version::compareVersion(RW_Version::VERSION));
        $this->assertContains(RW_Version::compareVersion(RW_Version::getLatest()), array(-1,0,1));
    }
}

