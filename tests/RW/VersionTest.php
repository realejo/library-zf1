<?php
require_once 'RW/Version.php';
/**
 * RW_Version test case.
 */
class VersionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_Version
     */
    private $RW_Version;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated VersionTest::setUp()
        $this->RW_Version = new RW_Version(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated VersionTest::tearDown()
        $this->RW_Version = null;
        parent::tearDown();
    }
    /**
     * Constructs the test case.
     */
    public function __construct ()
    {
        // TODO Auto-generated constructor
    }
}

