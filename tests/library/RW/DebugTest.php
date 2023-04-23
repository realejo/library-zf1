<?php

namespace RWTest;

use Exception;
use PHPUnit\Framework\TestCase;
use RW_Debug;

/**
 * RW_Debug test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class DebugTest extends TestCase
{
    /**
     * @var RW_Debug
     */
    private $RW_Debug;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // TODO Auto-generated DebugTest::setUp()
        $this->RW_Debug = new RW_Debug(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        // TODO Auto-generated DebugTest::tearDown()
        $this->RW_Debug = null;
        parent::tearDown();
    }

    /**
     * Tests RW_Debug::log()
     */
    public function testLog()
    {
        if (APPLICATION_ENV !== 'production') {
            self::assertInstanceOf('Zend_Log', $this->RW_Debug->log('Teste'));
        }
    }

    /**
     * Tests RW_Debug::logToFile()
     *
     * @todo ao invés de esperar exception, verificar se o arquivo de log existe
     */
    public function testLogToFile()
    {
        $this->expectException(Exception::class);
        self::assertInstanceOf('Zend_Log', $this->RW_Debug->logToFile('Teste'));
    }

    /**
     * Tests RW_Debug::sendError()
     */
    public function testSendError()
    {
        $error = RW_Debug::sendError('Teste', 404);

        self::assertEquals('Página não encontrada', $error);

        $error = RW_Debug::sendError('Teste', 500);

        self::assertEquals('Erro encontrado no site', $error);
    }
}

