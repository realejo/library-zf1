<?php
/**
 * RW_App_Loader_Awareness test case.
 */
class RW_App_Loader_AwarenessTest extends PHPUnit_Framework_TestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
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
     * Tests RW_App_Loader_Awareness->getLoader()
     */
    public function testGetLoader ()
    {
        $stub = $this->getMockForAbstractClass('RW_App_Loader_Awareness');
        $stub->expects($this->any())
             ->method('getLoader')
             ->will($this->returnValue(TRUE));

        $this->assertInstanceOf('RW_App_Loader', $stub->getLoader());
    }
    /**
     * Tests RW_App_Loader_Awareness->setLoader()
     */
    public function testSetLoader ()
    {
        $stub = $this->getMockForAbstractClass('RW_App_Loader_Awareness');
        $stub->expects($this->any())
             ->method('setLoader')
             ->will($this->returnValue(TRUE));

        $this->assertEquals('RW_App_Loader', $stub->setLoader('RW_App_Loader'));
    }
}

