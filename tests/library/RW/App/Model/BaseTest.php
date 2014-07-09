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

        $this->Base = new RW_App_Model_Base($this->tableName, $this->tableKeyName, $this->getAdapter());

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

        // Remove as pastas criadas
        $this->clearApplicationData();
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

        $Base = new RW_App_Model_Base($this->tableName, $this->tableKeyName);
        $this->assertInstanceOf('RW_App_Model_Base', $Base);
        $this->assertInstanceOf(get_class($this->getAdapter()), $Base->getTableGateway()->getAdapter(), 'tem o Adapter padrão');
        $this->assertEquals($this->getAdapter()->getConfig(), $Base->getTableGateway()->getAdapter()->getConfig(), 'tem a mesma configuração do adapter padrão');
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
    }


    /**
     * Tests Base->getWhere()
     */
    public function testWhere()
    {
        // Marca pra usar o campo deleted
        $this->Base->setUseDeleted(true);

        $this->assertEquals(array("{$this->tableName}.deleted=0"), $this->Base->getWhere(array("{$this->tableName}.deleted=0")));
        $this->assertEquals(array("{$this->tableName}.deleted=1"), $this->Base->getWhere(array("{$this->tableName}.deleted=1")));
    }

    /**
     * Tests campo deleted
     */
    public function testDeletedField()
    {
        // Verifica se deve remover o registro
        $this->Base->setUseDeleted(false);
        $this->assertFalse($this->Base->getUseDeleted());
        $this->assertTrue($this->Base->setUseDeleted(true)->getUseDeleted());
        $this->assertFalse($this->Base->setUseDeleted(false)->getUseDeleted());
        $this->assertFalse($this->Base->getUseDeleted());

        // Verifica se deve mostrar o registro
        $this->Base->setShowDeleted(false);
        $this->assertFalse($this->Base->getShowDeleted());
        $this->assertFalse($this->Base->setShowDeleted(false)->getShowDeleted());
        $this->assertTrue($this->Base->setShowDeleted(true)->getShowDeleted());
        $this->assertTrue($this->Base->getShowDeleted());
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

    }

    /**
     * Tests Base->testGetSQlSelect()
     */
    public function testGetSQlSelect()
    {
        $select = $this->Base->getTableSelect();
        $this->assertInstanceOf('Zend_Db_Table_Select', $select);
        $this->assertEquals($select->assemble(), $this->Base->getSQLString());
    }

    /**
     * Tests Base->fetchAll()
     */
    public function testFetchAll()
    {
         // O padrão é não usar o campo deleted
        $albuns = $this->Base->fetchAll();
        $this->assertCount(4, $albuns, 'showDeleted=false, useDeleted=false');

        // Marca para mostrar os removidos e não usar o campo deleted
        $this->Base->setShowDeleted(true)->setUseDeleted(false);
        $this->assertCount(4, $this->Base->fetchAll(), 'showDeleted=true, useDeleted=false');

        // Marca pra não mostar os removidos e usar o campo deleted
        $this->Base->setShowDeleted(false)->setUseDeleted(true);
        $this->assertCount(3, $this->Base->fetchAll(), 'showDeleted=false, useDeleted=true');

        // Marca pra mostrar os removidos e usar o campo deleted
        $this->Base->setShowDeleted(true)->setUseDeleted(true);
        $albuns = $this->Base->fetchAll();
        $this->assertCount(4, $albuns, 'showDeleted=true, useDeleted=true');

        // Marca não mostrar os removios
        $this->Base->setUseDeleted(true)->setShowDeleted(false);

        $albuns = $this->defaultValues;
        unset($albuns[3]); // remove o deleted=1
        $this->assertEquals($albuns, $this->Base->fetchAll());

        // Marca mostrar os removios
        $this->Base->setShowDeleted(true);

        $this->assertEquals($this->defaultValues, $this->Base->fetchAll());
        $this->assertCount(4, $this->Base->fetchAll());
        $this->Base->setShowDeleted(false);
        $this->assertCount(3, $this->Base->fetchAll());

        // Verifica o where
        $this->assertCount(2, $this->Base->fetchAll(array('artist'=>$albuns[0]['artist'])));
        $this->assertNull($this->Base->fetchAll(array('artist'=>$this->defaultValues[3]['artist'])));

        // Verifica o paginator com o padrão
        $paginator = $this->Base->setUsePaginator(true)->fetchAll();
        $paginator = $paginator->toJson();

        // Tem um bug no Zend_Paginator
        //http://framework.zend.com/issues/browse/ZF-9731
        $paginator = (array)json_decode($paginator);
        $temp = array();
        foreach($paginator as $p) {
            $temp[] = $p;
        }
        $paginator = json_encode($temp);

        $fetchAll = $this->Base->setUsePaginator(false)->fetchAll();
        $this->assertNotEquals(json_encode($this->defaultValues), $paginator);
        $this->assertEquals(json_encode($fetchAll), $paginator, 'retorno do paginator é igual');

        // Verifica o paginator alterando o paginator
        $this->Base->getPaginator()->setPageRange(2)
                                        ->setCurrentPageNumber(1)
                                        ->setItemCountPerPage(2);
        $paginator = $this->Base->setUsePaginator(true)->fetchAll();
        $paginator = $paginator->toJson();

        // Tem um bug no Zend_Paginator
        //http://framework.zend.com/issues/browse/ZF-9731
        $paginator = (array)json_decode($paginator);
        $temp = array();
        foreach($paginator as $p) {
            $temp[] = $p;
        }
        $paginator = json_encode($temp);

        $this->assertNotEquals(json_encode($this->defaultValues), $paginator);
        $fetchAll = $this->Base->setUsePaginator(false)->fetchAll(null, null, 2);
        $this->assertEquals(json_encode($fetchAll), $paginator);

        // Apaga qualquer cache
        $this->assertTrue($this->Base->getCache()->clean(), 'apaga o cache');

        // Define exibir os deletados
        $this->Base->setShowDeleted(true);

        // Liga o cache
        $this->Base->setUseCache(true);
        $this->assertEquals($this->defaultValues, $this->Base->fetchAll(), 'fetchAll está igual ao defaultValues');
        $this->assertCount(4, $this->Base->fetchAll(), 'Deve conter 4 registros');

        // Grava um registro "sem o cache saber"
        $this->Base->getTableGateway()->insert(array('id'=>10, 'artist'=>'nao existo por enquanto', 'title'=>'bla bla', 'deleted' => 0));

        $this->assertCount(4, $this->Base->fetchAll(), 'Deve conter 4 registros depois do insert "sem o cache saber"');
        $this->assertTrue($this->Base->getCache()->clean(), 'limpa o cache');
        $this->assertCount(5, $this->Base->fetchAll(), 'Deve conter 5 registros');

        // Define não exibir os deletados
        $this->Base->setShowDeleted(false);
        $this->assertCount(4, $this->Base->fetchAll(), 'Deve conter 4 registros com showDeleted=false');

        // Apaga um registro "sem o cache saber"
        $this->Base->getTableGateway()->delete("id=10");
        $this->Base->setShowDeleted(true);
        $this->assertCount(5, $this->Base->fetchAll(), 'Deve conter 5 registros');
        $this->assertTrue($this->Base->getCache()->clean(), 'apaga o cache');
        $this->assertCount(4, $this->Base->fetchAll(), 'Deve conter 4 registros 4');

    }

    /**
     * Tests Base->fetchRow()
     */
    public function testFetchRow()
    {
        // Marca pra usar o campo deleted
        $this->Base->setUseDeleted(true);

        // Verifica os itens que existem
        $this->assertEquals($this->defaultValues[0], $this->Base->fetchRow(1));
        $this->assertEquals($this->defaultValues[1], $this->Base->fetchRow(2));
        $this->assertEquals($this->defaultValues[2], $this->Base->fetchRow(3));

        // Verifica o item removido
        $this->Base->setShowDeleted(true);
        $this->assertEquals($this->defaultValues[3], $this->Base->fetchRow(4));
        $this->Base->setShowDeleted(false);
    }

    /**
     * Tests Base->fetchAssoc()
     */
    public function testFetchAssoc()
    {
        // O padrão é não usar o campo deleted
        $albuns = $this->Base->fetchAssoc();
        $this->assertCount(4, $albuns, 'showDeleted=false, useDeleted=false');
        $this->assertEquals($this->defaultValues[0], $albuns[1]);
        $this->assertEquals($this->defaultValues[1], $albuns[2]);
        $this->assertEquals($this->defaultValues[2], $albuns[3]);
        $this->assertEquals($this->defaultValues[3], $albuns[4]);

        // Marca para mostrar os removidos e não usar o campo deleted
        $this->Base->setShowDeleted(true)->setUseDeleted(false);
        $this->assertCount(4, $this->Base->fetchAssoc(), 'showDeleted=true, useDeleted=false');

        // Marca pra mostrar os removidos e usar o campo deleted
        $this->Base->setShowDeleted(true)->setUseDeleted(true);
        $albuns = $this->Base->fetchAssoc();
        $this->assertCount(4, $albuns, 'showDeleted=true, useDeleted=true');
        $this->assertEquals($this->defaultValues[0], $albuns[1]);
        $this->assertEquals($this->defaultValues[1], $albuns[2]);
        $this->assertEquals($this->defaultValues[2], $albuns[3]);
        $this->assertEquals($this->defaultValues[3], $albuns[4]);
    }
}

