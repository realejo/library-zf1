<?php
require_once 'RW/View/Helper/FileSize.php';
/**
 * RW_View_Helper_FileSize test case.
 */
class FileSizeTest extends PHPUnit_Framework_TestCase
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

