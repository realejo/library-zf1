<?php
/**
 * RW_Version test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
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

