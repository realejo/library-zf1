<?php
require_once 'RW/Spam.php';
/**
 * RW_Spam test case.
 */
class SpamTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_Spam
     */
    private $RW_Spam;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated SpamTest::setUp()
        $this->RW_Spam = new RW_Spam(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated SpamTest::tearDown()
        $this->RW_Spam = null;
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
     * Tests RW_Spam::isSpam()
     */
    public function testIsSpam ()
    {
        // TODO Auto-generated SpamTest::testIsSpam()
        $this->markTestIncomplete("isSpam test not implemented");
        RW_Spam::isSpam(/* parameters */);
    }
    /**
     * Tests RW_Spam->chkTrigger()
     */
    public function testChkTrigger ()
    {
        // TODO Auto-generated SpamTest->testChkTrigger()
        $this->markTestIncomplete("chkTrigger test not implemented");
        $this->RW_Spam->chkTrigger(/* parameters */);
    }
}

