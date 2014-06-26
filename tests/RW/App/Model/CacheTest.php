<?php
/**
 * CacheTest test case.
 *
 * @link      http://github.com/realejo/libraray-zf2
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */

/**
 * Base test case.
 */
class CacheTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var string
     */
    protected $dataPath = '/../../../assets/data';

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // Verifica se a pasta do cache existe
        if (file_exists($this->dataPath)) {
            $this->rrmdir($this->dataPath);
        }
        $oldumask = umask(0);
        mkdir($this->dataPath, 0777, true);
        umask($oldumask);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();

        // Remove as pastas criadas
        $this->rrmdir($this->dataPath);
    }

    /**
     * setAPPLICATION_DATA define o APPLICATION_DATA se não existir
     *
     * @return string
     */
    public function setAPPLICATION_DATA()
    {
        // Verifica se a pasta de cache existe
        if (defined('APPLICATION_DATA') === false) {
            define('APPLICATION_DATA', realpath(dirname(__FILE__)) .$this->dataPath);
        }
    }

    /**
     * Remove os arquivos da pasta e a pasta
     *
     * @return string
     */
    public function rrmdir($dir) {
        foreach(glob($dir . '/*') as $file) {
            if(is_dir($file)) {
                $this->rrmdir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dir);
    }

    /**
     * getCachePath sem nome da pasta
     *
     * @expectedException Exception
     */
    public function testGetCacheRootSemAPPLICATION_DATA()
    {
        RW_App_Model_Cache::getCacheRoot();
    }

    /**
     * getCachePath sem nome da pasta
     */
    public function testGetCacheRoot()
    {
        $this->setAPPLICATION_DATA();

        // Recupera a pasta aonde será salva as informações
        $path = RW_App_Model_Cache::getCacheRoot();

        // Verifica se tere o retorno correto
        $this->assertNotNull($path);
        $this->assertTrue(is_dir($path));
        $this->assertTrue(is_writable($path));
    }

    /**
     * getCachePath sem nome da pasta
     */
    public function testGetCachePath()
    {

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
    }
}

