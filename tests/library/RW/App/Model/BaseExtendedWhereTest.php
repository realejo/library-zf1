<?php

namespace RWTest\App\Model;

use RW_App_Model_Base;
use RWTest\TestAssets\BaseTestCase;
use Zend_Db_Table_Select;

/**
 * BaseTest test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class BaseExtendedWhereTest extends BaseTestCase
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

    protected $defaultValues = [
        [
            'id' => 1,
            'artist' => 'Rush',
            'title' => 'Rush',
            'deleted' => 0
        ],
        [
            'id' => 2,
            'artist' => 'Rush',
            'title' => 'Moving Pictures',
            'deleted' => 0
        ],
        [
            'id' => 3,
            'artist' => 'Dream Theater',
            'title' => 'Images And Words',
            'deleted' => 0
        ],
        [
            'id' => 4,
            'artist' => 'Claudia Leitte',
            'title' => 'Exttravasa',
            'deleted' => 1
        ]
    ];

    /**
     * @return self
     */
    public function insertDefaultRows()
    {
        foreach ($this->defaultValues as $row) {
            $this->getAdapter()->query(
                "INSERT into {$this->tableName}({$this->tableKeyName}, artist, title, deleted)
                                        VALUES (
                                            {$row[$this->tableKeyName]},
                                            '{$row['artist']}',
                                            '{$row['title']}',
                                            {$row['deleted']}
                                        );"
            );
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
        self::assertNull($this->Base->getOrder());

        // Define uma nova ordem com string
        $this->Base->setOrder('id');
        self::assertEquals('id', $this->Base->getOrder());

        // Define uma nova ordem com string
        $this->Base->setOrder('title');
        self::assertEquals('title', $this->Base->getOrder());

        // Define uma nova ordem com array
        $this->Base->setOrder(array('id', 'title'));
        self::assertEquals(array('id', 'title'), $this->Base->getOrder());

        // Teste o select alterado
        $order = $this->Base->getSelect(array('teste' => true))->getPart('order');
        self::assertCount(2, $order);
        self::assertEquals('id', $order[0][0]);
        self::assertEquals('title', $order[1][0]);
    }

    /**
     * Tests Base->getWhere()
     *
     * Apenas para ter o coverage completo
     */
    public function testWhere()
    {
        self::assertEquals('123456789abcde', $this->Base->getWhere('123456789abcde'));
        self::assertEquals(array('123456789abcde'), $this->Base->getWhere(array('test' => true, '123456789abcde')));
    }

    /**
     * Tests Base->getSQlString()
     */
    public function testGetSQlString()
    {
        // Verifica o padrão de não usar o campo deleted e não mostrar os removidos
        self::assertEquals(
            'SELECT `album`.* FROM `album`',
            $this->Base->getSQlString(),
            'showDeleted=false, useDeleted=false'
        );

        // Marca para usar o campo deleted
        $this->Base->setUseDeleted(true);
        self::assertEquals(
            'SELECT `album`.* FROM `album` WHERE (album.deleted = 0)',
            $this->Base->getSQlString(),
            'showDeleted=false, useDeleted=true'
        );

        // Marca para não usar o campo deleted
        $this->Base->setUseDeleted(false);

        self::assertEquals(
            'SELECT `album`.* FROM `album` WHERE (album.id = 1234)',
            $this->Base->getSQlString(array('id' => 1234))
        );
        self::assertEquals(
            "SELECT `album`.* FROM `album` WHERE (album.texto = 'textotextotexto')",
            $this->Base->getSQlString(array('texto' => 'textotextotexto'))
        );

        self::assertEquals(
            "SELECT `album`.* FROM `album` WHERE (album.id = 1234)",
            $this->Base->getSQlString(array('id' => 1234, 'test' => true))
        );
        self::assertEquals(
            "SELECT `album`.* FROM `album` WHERE (album.texto = 'textotextotexto')",
            $this->Base->getSQlString(array('texto' => 'textotextotexto', 'test' => true))
        );
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
