<?php

namespace RWTest\View\Helper;

use RW_View_Helper_FileSize;

/**
 * RW_View_Helper_FileSize test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */

class FileSizeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RW_View_Helper_FileSize
     */
    private $RW_View_Helper_FileSize;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated FileSizeTest::setUp()
        $this->RW_View_Helper_FileSize = new RW_View_Helper_FileSize(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated FileSizeTest::tearDown()
        $this->RW_View_Helper_FileSize = null;
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
     * Tests RW_View_Helper_FileSize->fileSize()
     */
    public function testFileSize ()
    {
        // TODO Auto-generated FileSizeTest->testFileSize()
        $this->markTestIncomplete("fileSize test not implemented");
        $this->RW_View_Helper_FileSize->fileSize(/* parameters */);
    }
}

