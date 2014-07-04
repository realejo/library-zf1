<?php
/**
 * RW_File test case.
 *
 * @link      http://github.com/realejo/library-zf1
 * @copyright Copyright (c) 2011-2014 Realejo Design Ltda. (http://www.realejo.com.br)
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

        $this->filePath = realpath(__DIR__ . '/_files/');

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

    	$file = $this->filePath .'/testFile.txt';
    	ob_start();
        $this->assertEquals(31, RW_File::readfile_chunked($file));
        ob_end_clean();
    }
}
