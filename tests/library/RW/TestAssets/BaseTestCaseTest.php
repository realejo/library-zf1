<?php

namespace RWTest\TestAssets;

use PHPUnit\Framework\TestCase;
use Zend_Application;

/**
 * Test case para as funcionalidades com conexão ao banco de dados
 *
 * @link      http://github.com/realejo/libraray-zf2
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class BaseTestCaseTest extends TestCase
{
    /**
     * @var BaseTestCase
     */
    private $BaseTestCase;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // TODO Auto-generated DbAdapterTest::setUp()

        $this->BaseTestCase = new BaseTestCase();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated DbAdapterTest::tearDown()
        $this->BaseTestCase = null;

        parent::tearDown();
    }

    /**
     * Prepares the environment before running ALL tests.
     */
    static public function setUpBeforeClass()
    {
        // Inicializa o ZF
        $inifile = (getenv('TRAVIS') !== false) ? 'application.travis.ini' : 'application.ini';
        $bootstrap = new Zend_Application(
            'testing',
            TEST_ROOT . '/assets/application/configs/' . $inifile
        );
        $bootstrap->bootstrap();
    }

    /**
     * Tests DbAdapter->getAdapter()
     */
    public function testGetAdapter()
    {
        $this->assertInstanceOf('Zend_Db_Adapter_Abstract', $this->BaseTestCase->getAdapter());
    }

    /**
     * Tests DbAdapter->testSetupMysql()
     */
    public function testTestSetupMysql()
    {
        $tables = array('album');
        $this->assertInstanceOf('BaseTestCase', $this->BaseTestCase->setTables($tables));
        $this->assertEquals($tables, $this->BaseTestCase->getTables());

        $dbTest = $this->BaseTestCase->createTables();
        $this->assertInstanceOf('BaseTestCase', $dbTest);

        $dbTest = $this->BaseTestCase->dropTables();
        $this->assertInstanceOf('BaseTestCase', $dbTest);

        $dbTest = $this->BaseTestCase->createTables()->dropTables();
        $this->assertInstanceOf('BaseTestCase', $dbTest);
    }

    public function testClearApplicationData()
    {
        // Verifica se está tudo ok
        $this->BaseTestCase->setApplicationConstants();
        if (!is_writable(APPLICATION_DATA)) {
            $this->fail('APPLICATION_DATA ' . APPLICATION_DATA . ' não tem permissão de escrita');
        }

        // Grava umas bobeiras la
        $folder = APPLICATION_DATA . '/teste1';
        if (!file_exists($folder)) {
            $oldumask = umask(0);
            mkdir($folder);
            umask($oldumask);
        }
        file_put_contents($folder . '/test1.txt', 'teste');

        $folder = APPLICATION_DATA . '/teste2/teste3';
        if (!file_exists($folder)) {
            $oldumask = umask(0);
            mkdir($folder, 0777, true);
            umask($oldumask);
        }
        file_put_contents($folder . '/sample.txt', 'teste teste');

        // Verifica se a pasta está vazia
        $this->assertFalse($this->BaseTestCase->isApplicationDataEmpty());

        $this->BaseTestCase->clearApplicationData();

        // Verifica se está vazia
        $files = $objects = scandir(APPLICATION_DATA);
        $this->assertCount(3, $files, 'não tem mais nada no APPLICATION_DATA');
        $this->assertEquals(array('.', '..', '.gitignore'), $files, 'não tem mais nada no APPLICATION_DATA');

        // Verifica se a pasta está vazia
        $this->assertTrue($this->BaseTestCase->isApplicationDataEmpty());

        // Grava mais coisa no raiz do APPLICATION_DATA
        file_put_contents(APPLICATION_DATA . '/sample.txt', 'outro teste');

        // Verifica se a pasta está vazia depois de apagar
        $this->assertFalse($this->BaseTestCase->isApplicationDataEmpty());
        $this->assertTrue($this->BaseTestCase->clearApplicationData());
    }
}

