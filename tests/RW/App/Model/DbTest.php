<?php
/**
 * TableAdapterTest test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */

/**
 * Db test case.
 */
class DbTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var string
     */
    protected $tableName = "album";

    /**
     *
     * @var string
     */
    protected $tableKeyName = "id";

    /**
     *
     * @var Zend\Db\Adapter\Adapter
     */
    protected $adapter = null;

    /**
     *
     * @var Db
     */
    private $Db;

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

    public function createTable()
    {
        $this->getAdapter()
        ->query("
            CREATE TABLE IF NOT EXISTS `{$this->tableName}`  (
            `{$this->tableKeyName}` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `artist` varchar(100) NOT NULL,
            `title` varchar(100) NOT NULL,
            `deleted` tinyint(1) unsigned NOT NULL default '0',
            PRIMARY KEY  (`{$this->tableKeyName}`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

            return $this;
    }

    public function dropTable()
    {
        $this->getAdapter()->query("DROP TABLE IF EXISTS {$this->tableName}");
        return $this;
    }

    public function truncateTable()
    {
        $this->dropTable()->createTable();
        return $this;
    }

    protected function setUp()
    {
        parent::setUp();
        $this->dropTable()->createTable();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->dropTable();
    }

    public function getDb($reset = false)
    {
        if ($this->Db === null || $reset === true) {
            $this->Db = new RW_App_Model_Db($this->tableName, $this->tableKeyName);
        }
        return $this->Db;
    }

    public function testTableName()
    {
        $this->assertEquals('album', $this->tableName);
    }

    /**
     * @expectedException Exception
     */
    public function testConstructSemTableName()
    {
        new RW_App_Model_Db(null, $this->tableKeyName);
    }

    /**
     * @expectedException Exception
     */
    public function testConstructSemKeyName()
    {
        new RW_App_Model_Db($this->tableName, null);
    }

    /**
     * Verifica se tudo foi criado corretamente no MySQL
     */
    public function testSetupMysql()
    {
        $this->setup();
        $this->assertTrue(true);
    }

    /**
     * Tests Db->insert()
     */
    public function testInsert()
    {
        // Certifica que a tabela está vazia
        $this->assertNull($this->getDb()->fetchAll(), 'Verifica se há algum registro pregravado');

        $this->assertFalse($this->getDb()->insert(array()), 'Verifica inclusão inválida 1');
        $this->assertFalse($this->getDb()->insert(null), 'Verifica inclusão inválida 2');

        $row = array(
            'artist' => 'Rush',
            'title' => 'Rush',
            'deleted' => '0'
        );

        $id = $this->getDb()->insert($row);
        $this->assertEquals(1, $id, 'Verifica a chave criada=1');

        $this->assertNotNull($this->getDb()->fetchAll(), 'Verifica o fetchAll não vazio');
        $this->assertEquals($row, $this->getDb()->getLastInsertSet(), 'Verifica o set do ultimo insert');
        $this->assertCount(1, $this->getDb()->fetchAll(), 'Verifica se apenas um registro foi adicionado');

        $row = array_merge(array('id'=>$id), $row);

        $this->assertEquals(array($row), $this->getDb()->fetchAll(), 'Verifica se o registro adicionado corresponde ao original pelo fetchAll()');
        $this->assertEquals($row, $this->getDb()->fetchRow(1), 'Verifica se o registro adicionado corresponde ao original pelo fetchRow()');

        $row = array(
            'id' => 2,
            'artist' => 'Rush',
            'title' => 'Test For Echos',
            'deleted' => '0'
        );

        $id = $this->getDb()->insert($row);
        $this->assertEquals(2, $id, 'Verifica a chave criada=2');

        $this->assertCount(2, $this->getDb()->fetchAll(), 'Verifica que há DOIS registro');
        $this->assertEquals($row, $this->getDb()->fetchRow(2), 'Verifica se o SEGUNDO registro adicionado corresponde ao original pelo fetchRow()');
        $this->assertEquals($row, $this->getDb()->getLastInsertSet());

        $row = array(
            'artist' => 'Rush',
            'title' => 'Moving Pictures',
            'deleted' => '0'
        );
        $id = $this->getDb()->insert($row);
        $this->assertEquals(3, $id);
        $this->assertEquals($row, $this->getDb()->getLastInsertSet(), 'Verifica se o TERCEIRO registro adicionado corresponde ao original pelo getLastInsertSet()');

        $row = array_merge(array('id'=>$id), $row);

        $this->assertCount(3, $this->getDb()->fetchAll());
        $this->assertEquals($row, $this->getDb()->fetchRow(3), 'Verifica se o TERCEIRO registro adicionado corresponde ao original pelo fetchRow()');

        // Teste com Zend_Db_Expr
        $id = $this->getDb()->insert(array('title'=>new Zend_Db_Expr('now()')));
        $this->assertEquals(4, $id);
    }
}

