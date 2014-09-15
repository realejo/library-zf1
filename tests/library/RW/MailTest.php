<?php
/**
 * RW_Mail test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class MailTest extends BaseTestCase
{
    /**
     * @var RW_Mail
     */
    private $RW_Mail;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();

        $this->setApplicationConstants();

        // TODO Auto-generated MailTest::setUp()
        $this->RW_Mail = new RW_Mail(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated MailTest::tearDown()
        $this->RW_Mail = null;
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
     * Tests RW_Mail->__construct()
     */
    public function test__construct ()
    {
        // TODO Auto-generated MailTest->test__construct()
        $this->markTestIncomplete("__construct test not implemented");
        $this->RW_Mail->__construct(/* parameters */);
    }

    /**
     * Tests RW_Mail->SendEmail()
     */
    public function testSendEmail ()
    {
        // TODO Auto-generated MailTest->testSendEmail()
        $this->markTestIncomplete("SendEmail test not implemented");
        $this->RW_Mail->SendEmail(/* parameters */);
    }

    /**
     * Tests RW_Mail->sendFeedback()
     */
    public function testSendFeedback ()
    {
        // TODO Auto-generated MailTest->testSendFeedback()
        $this->markTestIncomplete("sendFeedback test not implemented");
        $this->RW_Mail->sendFeedback(/* parameters */);
    }

    /**
     * Tests RW_Mail::isEmail()
     */
    public function testIsEmail ()
    {
        // TODO Auto-generated MailTest::testIsEmail()
        $this->markTestIncomplete("isEmail test not implemented");
        RW_Mail::isEmail(/* parameters */);
    }
}

