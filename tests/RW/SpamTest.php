<?php
/**
 * RW_Spam test case.
 *
 * @link      http://github.com/realejo/library-zf1
 * @copyright Copyright (c) 2011-2014 Realejo Design Ltda. (http://www.realejo.com.br)
 */
require_once 'RW/Spam.php';

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

        // TODO Auto-generated SpamTest->testChkTrigger()
        $this->markTestIncomplete("isSpam test not implemented");
        RW_Spam::isSpam(/* parameters */);


/*    	$this->assertEquals(false, RW_Spam::isSpam('teste', 'http://rw.rw', 'aqui'));
    	$this->assertEquals(true, RW_Spam::isSpam('teste', 'http://rw.rw', 'aqui estamos para testar está cialis htaccess lesbian'));
    	$this->assertEquals(false, RW_Spam::isSpam('teste', 'http://rw.rw', 'aqui estamos para testar está cialis'));
    	$this->assertEquals(false, RW_Spam::isSpam('teste', 'http://rw.rw', 'aqui estamos para testar está htaccess'));
    	$this->assertEquals(false, RW_Spam::isSpam('teste', 'http://rw.rw', 'aqui estamos para testar está lesbian'));
    	$this->assertEquals(false, RW_Spam::isSpam('teste', 'http://rw.rw', 'aqui estamos para testar está'));
    	$this->assertEquals(true, RW_Spam::isSpam('teste', 'http://rw.rw', 'aqui estamos para testar está porn fuckin bondage pennis windows free nursing'));
    	$this->assertEquals(true, RW_Spam::isSpam('teste', 'http://rw.rw', 'aqui estamos para testar está porn nipples lesbian <script> metabo acomplia'));
*/
    }
    /**
     * Tests RW_Spam->chkTrigger()
     */
    public function testChkTrigger ()
    {
        // TODO Auto-generated SpamTest->testChkTrigger()
        //$this->markTestIncomplete("chkTrigger test not implemented");
       // $this->RW_Spam->chkTrigger(/* parameters */);
        $texto1 = 'aqui estamos para testar está cialis viagra diazepam';
        $texto2 = 'aqui estamos para testar está lesbian nipples casino';
        $texto3 = 'aqui estamos para testar está hazzard nude online wedding free sex';
        $texto4 = 'aqui estamos';

        $this->assertEquals(false, $this->RW_Spam->chkTrigger($texto4));
        $this->assertEquals(true,  $this->RW_Spam->chkTrigger($texto1));
        $this->assertEquals(true,  $this->RW_Spam->chkTrigger($texto2));
        $this->assertEquals(true,  $this->RW_Spam->chkTrigger($texto3));





    }
}

