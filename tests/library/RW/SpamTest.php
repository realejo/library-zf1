<?php

namespace RWTest;

use PHPUnit\Framework\TestCase;
use RW_Spam;

/**
 * RW_Spam test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class SpamTest extends TestCase
{
    /**
     * @var RW_Spam
     */
    private $RW_Spam;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // TODO Auto-generated SpamTest::setUp()
        $this->RW_Spam = new RW_Spam(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        // TODO Auto-generated SpamTest::tearDown()
        $this->RW_Spam = null;
        parent::tearDown();
    }

    /**
     * Tests RW_Spam->chkTrigger()
     */
    public function testChkTrigger()
    {
        // TODO Auto-generated SpamTest->testChkTrigger()
        //$this->markTestIncomplete("chkTrigger test not implemented");
        // $this->RW_Spam->chkTrigger(/* parameters */);
        $texto1 = 'aqui estamos para testar está cialis viagra diazepam';
        $texto2 = 'aqui estamos para testar está lesbian nipples casino';
        $texto3 = 'aqui estamos para testar está hazzard nude online wedding free sex';
        $texto4 = 'aqui estamos';

       self::assertEquals(false, $this->RW_Spam->chkTrigger($texto4));
       self::assertEquals(true, $this->RW_Spam->chkTrigger($texto1));
       self::assertEquals(true, $this->RW_Spam->chkTrigger($texto2));
       self::assertEquals(true, $this->RW_Spam->chkTrigger($texto3));
    }
}

