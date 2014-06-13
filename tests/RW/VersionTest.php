<?php
/**
 * RW_Version test case.
 *
 * @link      http://github.com/realejo/library-zf1
 * @copyright Copyright (c) 2011-2014 Realejo Design Ltda. (http://www.realejo.com.br)
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

