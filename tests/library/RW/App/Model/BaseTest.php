<?php
/**
 * BaseTest test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class AppModelBaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $tableName = "album";

    /**
     * @var string
     */
    protected $tableKeyName = "id";

    /**
     * @var Base
     */
    private $Base;

    protected $defaultValues = array(
        array(
            'id' => 1,
            'artist' => 'Rush',
            'title' => 'Rush',
            'deleted' => 0
        ),
        array(
            'id' => 2,
            'artist' => 'Rush',
            'title' => 'Moving Pictures',
            'deleted' => 0
        ),
        array(
            'id' => 3,
            'artist' => 'Dream Theater',
            'title' => 'Images And Words',
            'deleted' => 0
        ),
        array(
            'id' => 4,
            'artist' => 'Claudia Leitte',
            'title' => 'Exttravasa',
            'deleted' => 1
        )
    );

    public function getAdapter()
    {
        if ($this->adapter === null) {

            $config = array(
                'host' => '192.168.100.25',
                'username' => 'root',
                'password' => 'naodigo',
                'dbname' => 'test',
                'charset' => 'UTF8');

            $db = Zend_Db::factory('Mysqli', $config);
            Zend_Db_Table_Abstract::setDefaultAdapter($db);
            $this->adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
        }
        return $this->adapter;
    }

    /**
     * @return \Realejo\Db\BaseTest
     */
    public function createTable()
    {
        $conn = $this->getAdapter();
        $conn->query("
            CREATE TABLE {$this->tableName} (
            {$this->tableKeyName} INTEGER PRIMARY KEY,
            artist varchar(100) NOT NULL,
            title varchar(100) NOT NULL,
            deleted INTEGER UNSIGNED NOT NULL DEFAULT 0
            );");

        return $this;
    }

    /**
     *
     * @return \Realejo\Db\BaseTest
     */
    public function insertDefaultRows()
    {
        $conn = $this->getAdapter();
        foreach ($this->defaultValues as $row) {
            $conn->query("INSERT into {$this->tableName}({$this->tableKeyName}, artist, title, deleted)
        VALUES ({$row[$this->tableKeyName]}, '{$row['artist']}', '{$row['title']}', {$row['deleted']});");
        }
        return $this;
    }

    /**
     *
     * @return \Realejo\Db\BaseTest
     */
    public function dropTable()
    {
        $this->getAdapter()->query("DROP TABLE IF EXISTS {$this->tableName}");
        return $this;
    }

    /**
     *
     * @return \Realejo\Db\BaseTest
     */
    public function truncateTable()
    {
        $this->dropTable()->createTable();
        return $this;
    }


    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->dropTable()->createTable()->insertDefaultRows();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->dropTable();
    }

    /**
     * @return RW_App_Model_Base
     */
    public function getBase($reset = false)
    {
        if ($this->Base === null || $reset === true) {
            $this->Base = new RW_App_Model_Base($this->tableName, $this->tableKeyName, $this->getAdapter());
        }
        return $this->Base;
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
            define('APPLICATION_DATA', $this->dataPath);
        }
    }

    /**
     * Construct sem nome da tabela
     * @expectedException Exception
     */
    public function testConstructSemTableName()
    {
        new RW_App_Model_Base(null, $this->tableKeyName);
    }

    /**
     * Construct sem nome da chave
     * @expectedException Exception
     */
    public function testConstructSemKeyName()
    {
        new RW_App_Model_Base($this->tableName, null);
    }

    /**
     * Constructs the test case copm adapter inválido. Ele deve ser Zend\Db\Adapter\Adapter\AdapterInterface
     * @expectedException Exception
     */
    public function testConstructComAdapterInvalido()
    {
        $Base = new RW_App_Model_Base($this->tableName, $this->tableKeyName, new \PDO('sqlite::memory:'));
    }

    /**
     * test a criação com a conexão local de testes
     */
    public function testCreateBase()
    {
        $Base = new RW_App_Model_Base($this->tableName, $this->tableKeyName, $this->getAdapter());
        $this->assertInstanceOf('RW_App_Model_Base', $Base);
    }

    /**
     * teste o adapter PDO
     */
    public function testPdoAdapter()
    {
        $this->assertInstanceOf('Zend_Db_Adapter_Abstract', $this->getAdapter());
        $this->assertInstanceOf('Zend_Db_Adapter_Abstract', $this->adapter);
    }


    /**
     * Tests Base->getOrder()
     */
    public function testOrder()
    {
        // Verifica a ordem padrão
        $this->assertNull($this->getBase()->getOrder());

        // Define uma nova ordem com string
        $this->getBase()->setOrder('id');
        $this->assertEquals('id', $this->getBase()->getOrder());

        // Define uma nova ordem com string
        $this->getBase()->setOrder('title');
        $this->assertEquals('title', $this->getBase()->getOrder());


        // Define uma nova ordem com array
        $this->getBase()->setOrder(array('id', 'title'));
        $this->assertEquals(array('id', 'title'), $this->getBase()->getOrder());
    }


    /**
     * Tests Base->getWhere()
     */
    public function testWhere()
    {

        // Marca pra usar o campo deleted
        $this->getBase()->setUseDeleted(true);

        $this->assertEquals(array("{$this->tableName}.deleted=0"), $this->getBase()->getWhere(array("{$this->tableName}.deleted=0")));
        $this->assertEquals(array("{$this->tableName}.deleted=1"), $this->getBase()->getWhere(array("{$this->tableName}.deleted=1")));
    }

    /**
     * Tests campo deleted
     */
    public function testDeletedField()
    {
        // Verifica se deve remover o registro
        $this->getBase()->setUseDeleted(false);
        $this->assertFalse($this->getBase()->getUseDeleted());
        $this->assertTrue($this->getBase()->setUseDeleted(true)->getUseDeleted());
        $this->assertFalse($this->getBase()->setUseDeleted(false)->getUseDeleted());
        $this->assertFalse($this->getBase()->getUseDeleted());

        // Verifica se deve mostrar o registro
        $this->getBase()->setShowDeleted(false);
        $this->assertFalse($this->getBase()->getShowDeleted());
        $this->assertFalse($this->getBase()->setShowDeleted(false)->getShowDeleted());
        $this->assertTrue($this->getBase()->setShowDeleted(true)->getShowDeleted());
        $this->assertTrue($this->getBase()->getShowDeleted());
    }

    /**
     * Tests Base->getSQlString()
     */
    public function testGetSQlString()
    {
        // Verfiica o padrão não usar o campo deleted e não mostrar os removidos
        $this->assertEquals('SELECT `album`.* FROM `album`', $this->getBase()->getSQlString(), 'showDeleted=false, useDeleted=false');

        // Marca para usar o campo deleted
        $this->getBase()->setUseDeleted(true);
        $this->assertEquals('SELECT `album`.* FROM `album`', $this->getBase()->getSQlString(), 'showDeleted=false, useDeleted=true');

        // Marca para não usar o campo deleted
        $this->getBase()->setUseDeleted(false);

        $this->assertEquals('SELECT `album`.* FROM `album` WHERE (album.id = 1234)', $this->getBase()->getSQlString(array('id'=>1234)));
        $this->assertEquals("SELECT `album`.* FROM `album` WHERE (album.texto = 'textotextotexto')", $this->getBase()->getSQlString(array('texto'=>'textotextotexto')));

    }

    /**
     * Tests Base->testGetSQlSelect()
     */
    public function testGetSQlSelect()
    {
        $select = $this->getBase()->getTableSelect();
        $this->assertInstanceOf('Zend_Db_Table_Select', $select);
        $this->assertEquals($select->assemble(), $this->getBase()->getSQLString());
    }

    /**
     * Tests Base->fetchAll()
     */
    public function testFetchAll()
    {

        // O padrão é não usar o campo deleted
        $albuns = $this->getBase()->fetchAll();
        $this->assertCount(4, $albuns, 'showDeleted=false, useDeleted=false');

        // Marca para mostrar os removidos e não usar o campo deleted
        $this->getBase()->setShowDeleted(true)->setUseDeleted(false);
        $this->assertCount(4, $this->getBase()->fetchAll(), 'showDeleted=true, useDeleted=false');

        // Marca pra não mostar os removidos e usar o campo deleted
        $this->getBase()->setShowDeleted(false)->setUseDeleted(true);
        $this->assertCount(4, $this->getBase()->fetchAll(), 'showDeleted=false, useDeleted=true');

        // Marca pra mostrar os removidos e usar o campo deleted
        $this->getBase()->setShowDeleted(true)->setUseDeleted(true);
        $albuns = $this->getBase()->fetchAll();
        $this->assertCount(4, $albuns, 'showDeleted=true, useDeleted=true');

        // Marca não mostrar os removios
        $this->getBase()->setShowDeleted(false);

        $albuns = $this->defaultValues;
        $this->assertEquals($albuns, $this->getBase()->fetchAll());

        // Marca mostrar os removios
        $this->getBase()->setShowDeleted(true);

        $this->assertEquals($this->defaultValues, $this->getBase()->fetchAll());
        $this->assertCount(4, $this->getBase()->fetchAll());
        $this->getBase()->setShowDeleted(false);
        $this->assertCount(4, $this->getBase()->fetchAll());

        // Verifica o where
        $this->assertCount(2, $this->getBase()->fetchAll(array('artist'=>$albuns[0]['artist'])));

        // Verifica o paginator com o padrão
        $paginator = $this->getBase()->setUsePaginator(true)->fetchAll();
        $paginator = $paginator->toJson();
        $fetchAll = $this->getBase()->setUsePaginator(false)->fetchAll();
        $this->assertNotEquals(json_encode($this->defaultValues), $paginator);

        // Verifica o paginator alterando o paginator
        $this->getBase()->getPaginator()->setPageRange(2)
                                        ->setCurrentPageNumber(1)
                                        ->setItemCountPerPage(2);
        $paginator = $this->getBase()->setUsePaginator(true)->fetchAll();
        $paginator = $paginator->toJson();
        $this->assertNotEquals(json_encode($this->defaultValues), $paginator);
        $fetchAll = $this->getBase()->setUsePaginator(false)->fetchAll(null, null, 2);

        $this->setAPPLICATION_DATA();

        // Define exibir os delatados
        $this->getBase()->setShowDeleted(true);

        // Liga o cache
        $this->getBase()->setUseCache(true);
        $this->assertEquals($this->defaultValues, $this->getBase()->fetchAll(), 'Igual');
        $this->assertCount(4, $this->getBase()->fetchAll(), 'Deve conter 4 registros 1');

        // Grava um registro "sem o cache saber"

        $this->assertCount(4, $this->getBase()->fetchAll(), 'Deve conter 4 registros 2');

        // Define não exibir os deletados
        $this->getBase()->setShowDeleted(false);
        $this->assertCount(4, $this->getBase()->fetchAll(), 'Deve conter 4 registros 3');

        // Apaga um registro "sem o cache saber"
        $this->getBase()->getTable()->delete(array("id"=>10));
        $this->getBase()->setShowDeleted(true);
        $this->assertCount(4, $this->getBase()->fetchAll(), 'Deve conter 4 registros 4');

    }

    /**
     * Tests Base->fetchRow()
     */
    public function testFetchRow()
    {
        // Marca pra usar o campo deleted
        $this->getBase()->setUseDeleted(true);

        // Verifica os itens que existem
        $this->assertEquals($this->defaultValues[0], $this->getBase()->fetchRow(1));
        $this->assertEquals($this->defaultValues[1], $this->getBase()->fetchRow(2));
        $this->assertEquals($this->defaultValues[2], $this->getBase()->fetchRow(3));

        // Verifica o item removido
        $this->getBase()->setShowDeleted(true);
        $this->assertEquals($this->defaultValues[3], $this->getBase()->fetchRow(4));
        $this->getBase()->setShowDeleted(false);
    }

    /**
     * Tests Base->fetchAssoc()
     */
    public function testFetchAssoc()
    {
        // O padrão é não usar o campo deleted
        $albuns = $this->getBase()->fetchAssoc();
        $this->assertCount(4, $albuns, 'showDeleted=false, useDeleted=false');
        $this->assertEquals($this->defaultValues[0], $albuns[1]);
        $this->assertEquals($this->defaultValues[1], $albuns[2]);
        $this->assertEquals($this->defaultValues[2], $albuns[3]);
        $this->assertEquals($this->defaultValues[3], $albuns[4]);

        // Marca para mostrar os removidos e não usar o campo deleted
        $this->getBase()->setShowDeleted(true)->setUseDeleted(false);
        $this->assertCount(4, $this->getBase()->fetchAssoc(), 'showDeleted=true, useDeleted=false');

        // Marca pra mostrar os removidos e usar o campo deleted
        $this->getBase()->setShowDeleted(true)->setUseDeleted(true);
        $albuns = $this->getBase()->fetchAssoc();
        $this->assertCount(4, $albuns, 'showDeleted=true, useDeleted=true');
        $this->assertEquals($this->defaultValues[0], $albuns[1]);
        $this->assertEquals($this->defaultValues[1], $albuns[2]);
        $this->assertEquals($this->defaultValues[2], $albuns[3]);
        $this->assertEquals($this->defaultValues[3], $albuns[4]);
    }
}

