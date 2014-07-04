<?php
/**
 * CacheTest test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class CacheTest extends BaseTestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // Não inicializa o APPLICATION_DATA pois alguns testes exigem que ele não exista
        //@todo é possível ser condicional? teste case separado?
        // Remove as pastas criadas
        //$this->clearApplicationData();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();

        // Não inicializa o APPLICATION_DATA pois alguns testes exigem que ele não exista
        //@todo é possível ser condicional? teste case separado?
        // Remove as pastas criadas
        //$this->clearApplicationData();
    }

    /**
     * getCachePath sem nome da pasta
     *
     * @expectedException Exception
     */
    public function testGetCacheRootSemAPPLICATION_DATA()
    {
        if (defined("APPLICATION_DATA")) {
            throw new Exception('APPLICATION_DATA já foi definido em outro lugar');
        }

        RW_App_Model_Cache::getCacheRoot();
    }

    /**
     * getCachePath sem nome da pasta
     */
    public function testGetCacheRoot()
    {
        $this->setApplicationConstants()->clearApplicationData();

        // Recupera a pasta aonde será salva as informações
        $path = RW_App_Model_Cache::getCacheRoot();

        // Verifica se tere o retorno correto
        $this->assertNotNull($path, 'a path foi retornado');
        $this->assertTrue(is_dir($path), "$path é um diretório");
        $this->assertTrue(is_writable($path), 'tem permissão de escrita');

        $this->clearApplicationData();
    }

    /**
     * getCachePath sem nome da pasta
     */
    public function testGetCachePath()
    {
        $this->setApplicationConstants()->clearApplicationData();

        // Verifica se todas as opções são iguais
        $this->assertEquals(RW_App_Model_Cache::getCacheRoot() . '/', RW_App_Model_Cache::getCachePath(null));
        $this->assertEquals(RW_App_Model_Cache::getCacheRoot() . '/', RW_App_Model_Cache::getCachePath(''));
        $this->assertEquals(RW_App_Model_Cache::getCacheRoot() . '/', RW_App_Model_Cache::getCachePath());

        // Cria ou recupera a pasta album
        $path = RW_App_Model_Cache::getCachePath('Album');

        // Verifica se foi criada corretamente a pasta
        $this->assertNotNull($path);
        $this->assertEquals(RW_App_Model_Cache::getCacheRoot() . '/album', $path);
        $this->assertNotEquals(RW_App_Model_Cache::getCacheRoot() . '/Album', $path);
        $this->assertTrue(file_exists($path));
        $this->assertTrue(is_dir($path));
        $this->assertTrue(is_writable($path));

        // Apaga a pasta
        $this->rrmdir($path);

        // Verifica se a pasta foi apagada
        $this->assertFalse(file_exists($path));

        // Cria ou recupera a pasta album
        $path = RW_App_Model_Cache::getCachePath('album');

        // Verifica se foi criada corretamente a pasta
        $this->assertNotNull($path);
        $this->assertEquals(RW_App_Model_Cache::getCacheRoot() . '/album', $path);
        $this->assertNotEquals(RW_App_Model_Cache::getCacheRoot() . '/Album', $path);
        $this->assertTrue(file_exists($path), 'Verifica se a pasta album existe');
        $this->assertTrue(is_dir($path), 'Verifica se a pasta album é uma pasta');
        $this->assertTrue(is_writable($path), 'Verifica se a pasta album tem permissão de escrita');

        // Apaga a pasta
        $this->rrmdir($path);

        // Verifica se a pasta foi apagada
        $this->assertFalse(file_exists($path));

        // Cria ou recupera a pasta
        $path = RW_App_Model_Cache::getCachePath('album_Teste');

        // Verifica se foi criada corretamente a pasta
        $this->assertNotNull($path);
        $this->assertEquals(RW_App_Model_Cache::getCacheRoot() . '/album/teste', $path);
        $this->assertNotEquals(RW_App_Model_Cache::getCacheRoot() . '/Album/Teste', $path);
        $this->assertTrue(file_exists($path), 'Verifica se a pasta album_Teste existe');
        $this->assertTrue(is_dir($path), 'Verifica se a pasta album_Teste é uma pasta');
        $this->assertTrue(is_writable($path), 'Verifica se a pasta album_Teste tem permissão de escrita');

        // Apaga a pasta
        $this->rrmdir($path);

        // Verifica se a pasta foi apagada
        $this->assertFalse(file_exists($path), 'Verifica se a pasta album_Teste foi apagada');

        // Cria ou recupera a pasta
        $path = RW_App_Model_Cache::getCachePath('album/Teste');

        // Verifica se foi criada corretamente a pasta
        $this->assertNotNull($path, 'Teste se o album/Teste foi criado');
        $this->assertEquals(RW_App_Model_Cache::getCacheRoot() . '/album/teste', $path);
        $this->assertNotEquals(RW_App_Model_Cache::getCacheRoot() . '/Album/Teste', $path);
        $this->assertTrue(file_exists($path), 'Verifica se a pasta album/Teste existe');
        $this->assertTrue(is_dir($path), 'Verifica se a pasta album/Teste é uma pasta');
        $this->assertTrue(is_writable($path), 'Verifica se a pasta album/Teste tem permissão de escrita');

        $this->clearApplicationData();
    }
}

