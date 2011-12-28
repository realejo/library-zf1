<?php
require_once 'RW/File.php';
/**
 * RW_File test case.
 */
class FileTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_File
     */
    private $RW_File;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated FileTest::setUp()
        $this->RW_File = new RW_File(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated FileTest::tearDown()
        $this->RW_File = null;
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
     * Tests RW_File::readfile_chunked()
     */
    public function testReadfile_chunked ()
    {
        // TODO Auto-generated FileTest::testReadfile_chunked()
        $this->markTestIncomplete(
        "readfile_chunked test not implemented");
        RW_File::readfile_chunked(/* parameters */);
    }
}

