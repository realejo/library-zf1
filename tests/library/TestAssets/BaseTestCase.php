<?php
/**
 * Test case para as funcionalidades padrões
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class BaseTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $adapter = null;

    /**
     * Lista de tabelas que serão criadas e dropadas
     *
     * @var array
     */
    protected $tables = array();

    public function __construct($tables = null)
    {
        if (!empty($tables) && is_array($tables)) {
            $this->tables = $tables;
        }
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

    function testBoostrap()
    {
        $this->assertTrue(true, 'Bootstrap OK');
    }

    /**
     * @return Zend_Db_Adapter_Abstract
     */
    public function getAdapter()
    {
        if (empty($this->adapter)) {
            $this->adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
        }
        return $this->adapter;
    }


    /**
     * Define as constantes necessárias nos models
     * Elas devem estar no index.php do projeto
     *
     * @return self
     */
    public function setApplicationConstants()
    {
        if (!defined('APPLICATION_PATH')) {
            define('APPLICATION_PATH', realpath(TEST_ROOT . '/assets/application'));
        }

        if (!defined('APPLICATION_DATA')) {
            define('APPLICATION_DATA', realpath(TEST_ROOT . '/assets/data'));
        }

        return $this;
    }

    /**
     *
     * @return SetupTest
     */
    public function createTables($tables = null)
    {
        if (empty($tables)) {
            $tables = $this->tables;
        }

        // Recupera o script para criar as tabelas
        foreach($tables as $tbl) {
            $create = TEST_ROOT  . "/assets/sql/$tbl.create.sql";
            if (!file_exists($create)) {
                $this->fail("create não encontrado em $create");
            }

            // Cria a tabela de usuários
            $this->getAdapter()->query(file_get_contents($create));
        }

        return $this;
    }

    /**
     * @return SetupTest
     */
    public function dropTables($tables = null)
    {
        if (empty($tables)) {
            $tables = array_reverse($this->tables);
        }

        if (!empty($tables)) {
            // Recupera o script para remover as tabelas
            foreach($tables as $tbl) {
                $drop = TEST_ROOT . "/assets/sql/$tbl.drop.sql";
                if (!file_exists($drop)) {
                    $this->fail("drop não encontrado em $drop");
                }

                // Remove a tabela de usuários
                $this->getAdapter()->query(file_get_contents($drop));
            }
        }

        return $this;
    }

    public function clearApplicationData()
    {
        // Verifica se há APPLICATION_DATA
        if (!defined('APPLICATION_DATA')) {
            $this->fail('APPLICATION_DATA não definido');
        }
        // Verifica se a pasta existe e tem permissão de escrita
        if (!is_dir(APPLICATION_DATA) || !is_writeable(APPLICATION_DATA)) {
            $this->fail('APPLICATION_DATA não definido');
        }

        // Apaga todo o conteudo dele
        $this->rrmdir(APPLICATION_DATA);

        return $this->isApplicationDataEmpty();
    }

    public function isApplicationDataEmpty()
    {
        // Verifica se há APPLICATION_DATA
        if (!defined('APPLICATION_DATA')) {
            $this->fail('APPLICATION_DATA não definido');
        }
        // Verifica se a pasta existe e tem permissão de escrita
        if (!is_dir(APPLICATION_DATA) || !is_writeable(APPLICATION_DATA)) {
            $this->fail('APPLICATION_DATA não definido');
        }

        // Retorna se está vazio
        return (count(scandir(APPLICATION_DATA)) == 3);
    }

    public function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != ".." && $object != ".gitignore") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            // Não apaga o APPLICATION_DATA
            if ($dir != APPLICATION_DATA) {
                rmdir($dir);
            }
        }
    }


    /**
     * Retorna as tabelas padrões
     *
     * @return array
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * Define as tabelas a serem usadas com padrão
     *
     * @param array $tables
     *
     * @return BaseTestCase
     */
    public function setTables($tables)
    {
        $this->tables = $tables;

        return $this;
    }

    /**
     * Call protected/private method of a class.
     *
     * @see https://jtreminio.com/2013/03/unit-testing-tutorial-part-3-testing-protected-private-methods-coverage-reports-and-crap/
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}
