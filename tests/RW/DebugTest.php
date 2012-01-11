<?php
/**
 * RW_Debug test case.
 *
 * @category   RW
 * @package    RW_Debug
 * @subpackage UnitTests
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */

require_once 'RW/Debug.php';

class DebugTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_Debug
     */
    private $RW_Debug;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated DebugTest::setUp()
        $this->RW_Debug = new RW_Debug(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated DebugTest::tearDown()
        $this->RW_Debug = null;
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
     * Tests RW_Debug::log()
     */
    public function testLog ()
    {
        // TODO Auto-generated DebugTest::testLog()
        $this->markTestIncomplete("log test not implemented");
        $logger = new Zend_Log();
        RW_Debug::log('MENSAGEM DE ERROR', 1);
    }
    /**
     * Tests RW_Debug::logToFile()
     */
    public function testLogToFile ()
    {
        // TODO Auto-generated DebugTest::testLogToFile()
        $this->markTestIncomplete("logToFile test not implemented");
        RW_Debug::logToFile(/* parameters */);
    }
    /**
     * Tests RW_Debug::sendError()
     */
    public function testSendError ()
    {
        // TODO Auto-generated DebugTest::testSendError()
        $this->markTestIncomplete("sendError test not implemented");
        RW_Debug::sendError(/* parameters */);
    }
}

