<?php
/**
 * BaseTest test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class AppModelBaseTest extends BaseTestCase
{
    /**
     * @var string
     */
    protected $tableName = "album";

    /**
     * @var string
     */
    protected $tableKeyName = "id";

    protected $tables = array('album');

    /**
     * @var RW_App_Model_Base
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

    /**
     * @return self
     */
    public function insertDefaultRows()
    {
        foreach ($this->defaultValues as $row) {
            $this->getAdapter()->query("INSERT into {$this->tableName}({$this->tableKeyName}, artist, title, deleted)
                                        VALUES (
                                            {$row[$this->tableKeyName]},
                                            '{$row['artist']}',
                                            '{$row['title']}',
                                            {$row['deleted']}
                                        );");
        }
        return $this;
    }

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->dropTables()->createTables()->insertDefaultRows();

        $this->Base = new BaseExtended($this->tableName, $this->tableKeyName, $this->getAdapter());

        // Remove as pastas criadas
        $this->setApplicationConstants()->clearApplicationData();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->dropTables();

        unset($this->Base);

        $this->clearApplicationData();
    }

    /**
     * Tests Base->getOrder()
     */
    public function testOrder()
    {
        // Verifica a ordem padrão
        $this->assertNull($this->Base->getOrder());

        // Define uma nova ordem com string
        $this->Base->setOrder('id');
        $this->assertEquals('id', $this->Base->getOrder());

        // Define uma nova ordem com string
        $this->Base->setOrder('title');
        $this->assertEquals('title', $this->Base->getOrder());

        // Define uma nova ordem com array
        $this->Base->setOrder(array('id', 'title'));
        $this->assertEquals(array('id', 'title'), $this->Base->getOrder());

        // Teste o select alterado
        $order = $this->Base->getSelect(array('teste'=>true))->getPart('order');
        $this->assertCount(2, $order);
        $this->assertEquals('id', $order[0][0]);
        $this->assertEquals('title', $order[1][0]);
    }

    /**
     * Tests Base->getWhere()
     *
     * Apenas para ter o coverage completo
     */
    public function testWhere()
    {
        $this->assertEquals('123456789abcde', $this->Base->getWhere('123456789abcde'));
        $this->assertEquals(array('123456789abcde'), $this->Base->getWhere(array('test'=>true, '123456789abcde')));
    }

    /**
     * Tests Base->getSQlString()
     */
    public function testGetSQlString()
    {
        // Verifica o padrão de não usar o campo deleted e não mostrar os removidos
        $this->assertEquals('SELECT `album`.* FROM `album`', $this->Base->getSQlString(), 'showDeleted=false, useDeleted=false');

        // Marca para usar o campo deleted
        $this->Base->setUseDeleted(true);
        $this->assertEquals('SELECT `album`.* FROM `album` WHERE (album.deleted = 0)', $this->Base->getSQlString(), 'showDeleted=false, useDeleted=true');

        // Marca para não usar o campo deleted
        $this->Base->setUseDeleted(false);

        $this->assertEquals('SELECT `album`.* FROM `album` WHERE (album.id = 1234)', $this->Base->getSQlString(array('id'=>1234)));
        $this->assertEquals("SELECT `album`.* FROM `album` WHERE (album.texto = 'textotextotexto')", $this->Base->getSQlString(array('texto'=>'textotextotexto')));

        $this->assertEquals("SELECT `album`.*, `album`.`id` AS `novoid` FROM `album` WHERE (album.id = 1234)", $this->Base->getSQlString(array('id'=>1234, 'test'=>true)));
        $this->assertEquals("SELECT `album`.*, `album`.`id` AS `novoid` FROM `album` WHERE (album.texto = 'textotextotexto')", $this->Base->getSQlString(array('texto'=>'textotextotexto', 'test'=>true)));

    }
}


class BaseExtended extends RW_App_Model_Base
{

    /**
     * (non-PHPdoc)
     * @see RW_App_Model_Base::getWhere()
     */
    public function getWhere($where, &$select = null)
    {
        if (isset($where['test'])) {
            if ($select instanceof Zend_Db_Table_Select) {
                $select->columns("id as novoid");
            }
            unset($where['test']);
        }

        return parent::getWhere($where, $select);
    }
}
