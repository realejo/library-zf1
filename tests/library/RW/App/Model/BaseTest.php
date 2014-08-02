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

        $this->clearApplicationData();
    }

    /**
     * Construct sem nome da tabela
     * @expectedException InvalidArgumentException
     */
    public function testConstructSemTableName()
    {
        new RW_App_Model_Base(null, $this->tableKeyName);
    }

    /**
     * Construct sem nome da chave
     * @expectedException InvalidArgumentException
     */
    public function testConstructSemKeyName()
    {
        new RW_App_Model_Base($this->tableName, null);
    }

    /**
     * Definição de chave invalido
     * @expectedException InvalidArgumentException
     */
    public function testKeyNameInvalido()
    {
        $this->Base->setKey(null);
    }

    /**
     * Definição de ordem invalido
     * @expectedException InvalidArgumentException
     */
    public function testOrderInvalida()
    {
        $this->Base->setOrder(null);
    }

    /**
     * Definição de ordem invalido
     * @expectedException InvalidArgumentException
     */
    public function testfetchRowMultiKeyException()
    {
        // Cria a tabela com chave string
        $this->Base->setKey(array(RW_App_Model_Base::KEY_INTEGER=>'id_int', RW_App_Model_Base::KEY_STRING=>'id_char'));
        $this->Base->fetchRow(1);
    }

    /**
     * Definição de chave invalido
     */
    public function testGettersStters()
    {
        $this->assertEquals('meuid', $this->Base->setKey('meuid')->getKey());
        $this->assertEquals('meuid', $this->Base->setKey('meuid')->getKey(true));
        $this->assertEquals('meuid', $this->Base->setKey('meuid')->getKey(false));

        $this->assertEquals(array('meuid', 'com array'), $this->Base->setKey(array('meuid', 'com array'))->getKey());
        $this->assertEquals(array('meuid', 'com array'), $this->Base->setKey(array('meuid', 'com array'))->getKey(false));
        $this->assertEquals('meuid', $this->Base->setKey(array('meuid', 'com array'))->getKey(true));

        $this->assertInstanceOf('Zend_Db_Expr', $this->Base->setKey(new Zend_Db_Expr('chave muito exotica!'))->getKey());
        $this->assertInstanceOf('Zend_Db_Expr', $this->Base->setKey(array(new Zend_Db_Expr('chave muito mais exotica!'), 'não existo'))->getKey(true));

        $this->assertEquals('minhaordem', $this->Base->setOrder('minhaordem')->getOrder());
        $this->assertEquals(array('minhaordem', 'comarray'), $this->Base->setOrder(array('minhaordem', 'comarray'))->getOrder());
        $this->assertInstanceOf('Zend_Db_Expr', $this->Base->setOrder(new Zend_Db_Expr('ordem muito exotica!'))->getOrder());
    }

    /**
     * Test de criação com a conexão local de testes
     */
    public function testCreateBase()
    {
        $Base = new RW_App_Model_Base($this->tableName, $this->tableKeyName);
        $this->assertInstanceOf('RW_App_Model_Base', $Base);
        $this->assertEquals($this->tableKeyName, $Base->getKey());
        $this->assertEquals($this->tableName, $Base->getTable());

        $Base = new RW_App_Model_Base($this->tableName, array($this->tableKeyName, $this->tableKeyName));
        $this->assertInstanceOf('RW_App_Model_Base', $Base);
        $this->assertEquals(array($this->tableKeyName, $this->tableKeyName), $Base->getKey());
        $this->assertEquals($this->tableName, $Base->getTable());

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
     *
     * Apenas para ter o coverage completo
     */
    public function testWhere()
    {
        $this->assertEquals('123456789abcde', $this->Base->getWhere('123456789abcde'));
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
     * Tests Base->fetchRow()
     */
    public function testFetchRowWithIntegerKey()
    {
        $this->Base->setKey(array(RW_App_Model_Base::KEY_INTEGER=>'id'));

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
     * Tests Base->fetchRow()
     */
    public function testFetchRowWithStringKey()
    {
        $this->dropTables()->createTables(array('album_string'));
        $defaultValues = array(
                array(
                        'id' => 'A',
                        'artist' => 'Rush',
                        'title' => 'Rush',
                        'deleted' => 0
                ),
                array(
                        'id' => 'B',
                        'artist' => 'Rush',
                        'title' => 'Moving Pictures',
                        'deleted' => 0
                ),
                array(
                        'id' => 'C',
                        'artist' => 'Dream Theater',
                        'title' => 'Images And Words',
                        'deleted' => 0
                ),
                array(
                        'id' => 'D',
                        'artist' => 'Claudia Leitte',
                        'title' => 'Exttravasa',
                        'deleted' => 1
                )
        );
        foreach ($defaultValues as $row) {
            $this->getAdapter()->query("INSERT into {$this->tableName}({$this->tableKeyName}, artist, title, deleted)
                                        VALUES (
                                        '{$row['id']}',
                                        '{$row['artist']}',
                                        '{$row['title']}',
                                        {$row['deleted']}
                                        );");
        }

        $this->Base->setKey(array(RW_App_Model_Base::KEY_STRING=>'id'));

        // Marca pra usar o campo deleted
        $this->Base->setUseDeleted(true);

        // Verifica os itens que existem
        $this->assertEquals($defaultValues[0], $this->Base->fetchRow('A'));
        $this->assertEquals($defaultValues[1], $this->Base->fetchRow('B'));
        $this->assertEquals($defaultValues[2], $this->Base->fetchRow('C'));

        // Verifica o item removido
        $this->Base->setShowDeleted(true);
        $this->assertEquals($defaultValues[3], $this->Base->fetchRow('D'));
        $this->Base->setShowDeleted(false);
    }

    /**
     * Tests Base->fetchRow()
     */
    public function testFetchRowWithMultipleKey()
    {
        $this->dropTables()->createTables(array('album_array'));
        $defaultValues = array(
                array(
                        'id_int' => 1,
                        'id_char' => 'A',
                        'artist' => 'Rush',
                        'title' => 'Rush',
                        'deleted' => 0
                ),
                array(
                        'id_int' => 2,
                        'id_char' => 'B',
                        'artist' => 'Rush',
                        'title' => 'Moving Pictures',
                        'deleted' => 0
                ),
                array(
                        'id_int' => 3,
                        'id_char' => 'C',
                        'artist' => 'Dream Theater',
                        'title' => 'Images And Words',
                        'deleted' => 0
                ),
                array(
                        'id_int' => 4,
                        'id_char' => 'D',
                        'artist' => 'Claudia Leitte',
                        'title' => 'Exttravasa',
                        'deleted' => 1
                )
        );
        foreach ($defaultValues as $row) {
            $this->getAdapter()->query("INSERT into album (id_int, id_char, artist, title, deleted)
                                        VALUES (
                                        '{$row['id_int']}',
                                        '{$row['id_char']}',
                                        '{$row['artist']}',
                                        '{$row['title']}',
                                        {$row['deleted']}
                                        );");
        }

        $this->Base->setKey(array(RW_App_Model_Base::KEY_STRING=>'id'));

        // Marca pra usar o campo deleted
        $this->Base->setUseDeleted(true);

        // Verifica os itens que existem
        $this->assertEquals($defaultValues[0], $this->Base->fetchRow(array('id_char'=>'A', 'id_int'=>1)));
        $this->assertEquals($defaultValues[1], $this->Base->fetchRow(array('id_char'=>'B', 'id_int'=>2)));
        $this->assertEquals($defaultValues[2], $this->Base->fetchRow(array('id_char'=>'C', 'id_int'=>3)));

        $this->assertNull($this->Base->fetchRow(array('id_char'=>'C', 'id_int'=>2)));

        // Verifica o item removido
        $this->Base->setShowDeleted(true);
        $this->assertEquals($defaultValues[3], $this->Base->fetchRow(array('id_char'=>'D', 'id_int'=>4)));
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

    /**
     * Tests Base->fetchAssoc()
     */
    public function testFetchAssocWithMultipleKeys()
    {
        $this->Base->setKey(array($this->tableKeyName, 'naoexisto'));

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

    public function testHtmlSelectGettersSetters()
    {
        $this->assertEquals('{nome}', $this->Base->getHtmlSelectOption(), 'padrão {nome}');
        $this->assertInstanceOf('RW_App_Model_Base', $this->Base->setHtmlSelectOption('{title}'), 'setHtmlSelectOption() retorna RW_App_Model_Base');
        $this->assertEquals('{title}', $this->Base->getHtmlSelectOption(), 'troquei por {title}');
    }

    public function testHtmlSelectWhere()
    {
        $id = 'teste';
        $this->Base->setHtmlSelectOption('{title}');

        $select = $this->Base->getHtmlSelect($id, null, array('where'=>array('artist'=>'Rush')));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        $this->assertCount(3, $options, " 3 opções encontradas");

        $this->assertEmpty($options->current()->nodeValue, "primeiro é vazio 1");
        $this->assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio 1");

        $options->next();
        $this->assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 1");
        $this->assertEquals($this->defaultValues[0]['id'], $options->current()->getAttribute('value'), "valor do segundo ok 1");

        $options->next();
        $this->assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 1");
        $this->assertEquals($this->defaultValues[1]['id'], $options->current()->getAttribute('value'), "valor do terceiro ok 1");


        $select = $this->Base->getHtmlSelect($id, 1, array('where'=>array('artist'=>'Rush')));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        $this->assertCount(2, $options, " 2 opções encontradas");

        $this->assertNotEmpty($options->current()->nodeValue, "primeiro não é vazio 2");
        $this->assertNotEmpty($options->current()->getAttribute('value'), "o valor do primeiro não é vazio 2");

        $this->assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 2");
        $this->assertEquals($this->defaultValues[0]['id'], $options->current()->getAttribute('value'), "valor do segundo ok 2");

        $options->next();
        $this->assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 2");
        $this->assertEquals($this->defaultValues[1]['id'], $options->current()->getAttribute('value'), "valor do terceiro ok 2");
    }

    public function testHtmlSelectSemOptionValido()
    {
        $id = 'teste';

        $select = $this->Base->getHtmlSelect($id);
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        $this->assertCount(5, $options, " 5 opções encontradas");

        $this->assertEmpty($options->current()->nodeValue, "primeiro é vazio 1");
        $this->assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio 1");

        $options->next();
        $this->assertEmpty($options->current()->nodeValue, "nome do segundo ok 1");
        $this->assertEquals($this->defaultValues[0]['id'], $options->current()->getAttribute('value'), "valor do segundo ok 1");

        $options->next();
        $this->assertEmpty($options->current()->nodeValue, "nome do terceiro ok 1");
        $this->assertEquals($this->defaultValues[1]['id'], $options->current()->getAttribute('value'), "valor do terceiro ok 1");

        $options->next();
        $this->assertEmpty($options->current()->nodeValue, "nome do quarto ok 1");
        $this->assertEquals($this->defaultValues[2]['id'], $options->current()->getAttribute('value'), "valor do quarto ok 1");

        $options->next();
        $this->assertEmpty($options->current()->nodeValue, "nome do quinto ok 1");
        $this->assertEquals($this->defaultValues[3]['id'], $options->current()->getAttribute('value'), "valor do quinto ok 1");

        $select = $this->Base->setHtmlSelectOption('{nao_existo}')->getHtmlSelect($id);
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        $this->assertCount(5, $options, " 5 opções encontradas");

        $this->assertEmpty($options->current()->nodeValue, "primeiro é vazio 2");
        $this->assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio 2");

        $options->next();
        $this->assertEmpty($options->current()->nodeValue, "nome do segundo ok 2");
        $this->assertEquals($this->defaultValues[0]['id'], $options->current()->getAttribute('value'), "valor do segundo ok 2");

        $options->next();
        $this->assertEmpty($options->current()->nodeValue, "nome do terceiro ok 2");
        $this->assertEquals($this->defaultValues[1]['id'], $options->current()->getAttribute('value'), "valor do terceiro ok 2");

        $options->next();
        $this->assertEmpty($options->current()->nodeValue, "nome do quarto ok 2");
        $this->assertEquals($this->defaultValues[2]['id'], $options->current()->getAttribute('value'), "valor do quarto ok 2");

        $options->next();
        $this->assertEmpty($options->current()->nodeValue, "nome do quinto ok 2");
        $this->assertEquals($this->defaultValues[3]['id'], $options->current()->getAttribute('value'), "valor do quinto ok 2");
    }

    public function testHtmlSelectOption()
    {
        $id = 'teste';

        $select = $this->Base->setHtmlSelectOption('{artist}')->getHtmlSelect($id);
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        $this->assertCount(1, $dom->query("#$id"), "id #$id existe");
        $this->assertCount(1, $dom->query("select[name=\"$id\"]"), "placeholder select[name=\"$id\"] encontrado");
        $options = $dom->query("option");
        $this->assertCount(5, $options, " 5 opções encontradas");

        $this->assertEmpty($options->current()->nodeValue, "primeiro é vazio");
        $this->assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio");

        $options->next();
        $this->assertEquals($this->defaultValues[0]['artist'], $options->current()->nodeValue, "nome do segundo ok");
        $this->assertEquals($this->defaultValues[0]['id'], $options->current()->getAttribute('value'), "valor do segundo ok");

        $options->next();
        $this->assertEquals($this->defaultValues[1]['artist'], $options->current()->nodeValue, "nome do terceiro ok");
        $this->assertEquals($this->defaultValues[1]['id'], $options->current()->getAttribute('value'), "valor do terceiro ok");

        $options->next();
        $this->assertEquals($this->defaultValues[2]['artist'], $options->current()->nodeValue, "nome do quarto ok");
        $this->assertEquals($this->defaultValues[2]['id'], $options->current()->getAttribute('value'), "valor do quarto ok");

        $options->next();
        $this->assertEquals($this->defaultValues[3]['artist'], $options->current()->nodeValue, "nome do quinto ok");
        $this->assertEquals($this->defaultValues[3]['id'], $options->current()->getAttribute('value'), "valor do quinto ok");
    }

    public function testHtmlSelectPlaceholder()
    {
        $ph = 'myplaceholder';
        $select = $this->Base->getHtmlSelect('nome_usado', null, array('placeholder'=>$ph));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        $this->assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe');
        $this->assertCount(1, $dom->query("select[placeholder=\"$ph\"]"), "placeholder select[placeholder=\"$ph\"] encontrado");
        $options = $dom->query("option");
        $this->assertCount(5, $options, " 5 opções encontradas");
        $this->assertEquals($ph, $options->current()->nodeValue, "placeholder é a primeira");
        $this->assertEmpty($options->current()->getAttribute('value'), "o valor do placeholder é vazio");
    }

    public function testHtmlSelectShowEmpty()
    {
        $select = $this->Base->getHtmlSelect('nome_usado');
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        $this->assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe');
        $this->assertCount(5, $dom->query('option'), '5 opções existem');
        $this->assertEmpty($dom->query('option')->current()->nodeValue, "a primeira é vazia");
        $this->assertEmpty($dom->query('option')->current()->getAttribute('value'), "o valor da primeira é vazio");

        $select = $this->Base->getHtmlSelect('nome_usado', 1);
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        $this->assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe COM valor padrão');
        $this->assertCount(4, $dom->query('option'), '4 opções existem COM valor padrão');

        $select = $this->Base->getHtmlSelect('nome_usado', null, array('show-empty'=>false));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        $this->assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe SEM valor padrão e show-empty=false');
        $this->assertCount(4, $dom->query('option'), '4 opções existem SEM valor padrão e show-empty=false');

        // sem mostrar o empty
        $select = $this->Base->getHtmlSelect('nome_usado', 1, array('show-empty'=>false));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        $this->assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe com valor padrão e show-empty=false');
        $this->assertCount(4, $dom->query('option'), '4 opções existem com valor padrão e show-empty=false');

        // sem mostrar o empty
        $select = $this->Base->getHtmlSelect('nome_usado', 1, array('show-empty'=>true));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        $this->assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe com valor padrão e show-empty=true');
        $this->assertCount(5, $dom->query('option'), '5 opções existem com valor padrão e show-empty=true');
        $this->assertEmpty($dom->query('option')->current()->nodeValue, "a primeira é vazia com valor padrão e show-empty=true");
        $this->assertEmpty($dom->query('option')->current()->getAttribute('value'), "o valor da primeira é vazio com valor padrão e show-empty=true");
    }

    public function testHtmlSelectGrouped()
    {
        $id = 'teste';

        $select = $this->Base->setHtmlSelectOption('{title}')->getHtmlSelect($id, 1, array('grouped'=>'artist'));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        $this->assertCount(1, $dom->query("#$id"), "id #$id existe");

        $options = $dom->query("option");
        $this->assertCount(4, $options, " 4 opções encontradas");

        $this->assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do primeiro ok 1");
        $this->assertEquals($this->defaultValues[0]['id'], $options->current()->getAttribute('value'), "valor do primeiro ok 1");

        $options->next();
        $this->assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do segundo ok 1");
        $this->assertEquals($this->defaultValues[1]['id'], $options->current()->getAttribute('value'), "valor do segundo ok 1");

        $options->next();
        $this->assertEquals($this->defaultValues[2]['title'], $options->current()->nodeValue, "nome do terceiro ok 1");
        $this->assertEquals($this->defaultValues[2]['id'], $options->current()->getAttribute('value'), "valor do terceiro ok 1");

        $options->next();
        $this->assertEquals($this->defaultValues[3]['title'], $options->current()->nodeValue, "nome do quarto ok 1");
        $this->assertEquals($this->defaultValues[3]['id'], $options->current()->getAttribute('value'), "valor do quarto ok 1");

        $optgroups = $dom->query("optgroup");
        $this->assertCount(3, $optgroups, " 3 grupo de opções encontrados");

        $this->assertEquals($this->defaultValues[0]['artist'], $optgroups->current()->getAttribute('label'), "nome do primeiro grupo ok");
        $this->assertEquals(2, $optgroups->current()->childNodes->length, " 2 opções encontrados no priemiro optgroup");
        $this->assertEquals($this->defaultValues[0]['title'], $optgroups->current()->firstChild->nodeValue, "nome do primeiro ok 2");
        $this->assertEquals($this->defaultValues[0]['id'], $optgroups->current()->firstChild->getAttribute('value'), "valor do primeiro ok 2");
        $this->assertEquals($this->defaultValues[1]['title'], $optgroups->current()->firstChild->nextSibling->nodeValue, "nome do segundo ok 2");
        $this->assertEquals($this->defaultValues[1]['id'], $optgroups->current()->firstChild->nextSibling->getAttribute('value'), "valor do segundo ok 2");

        $optgroups->next();
        $this->assertEquals($this->defaultValues[2]['artist'], $optgroups->current()->getAttribute('label'), "nome do segundo grupo ok");
        $this->assertEquals(1, $optgroups->current()->childNodes->length, " 2 opções encontrados");
        $this->assertEquals($this->defaultValues[2]['title'], $optgroups->current()->firstChild->nodeValue, "nome do terceiro ok 2");
        $this->assertEquals($this->defaultValues[2]['id'], $optgroups->current()->firstChild->getAttribute('value'), "valor do terceiro ok 2");

        $optgroups->next();
        $this->assertEquals($this->defaultValues[3]['artist'], $optgroups->current()->getAttribute('label'), "nome do terceiro grupo ok");
        $this->assertEquals(1, $optgroups->current()->childNodes->length, " 2 opções encontrados");
        $this->assertEquals($this->defaultValues[3]['title'], $optgroups->current()->firstChild->nodeValue, "nome do terceiro ok 2");
        $this->assertEquals($this->defaultValues[3]['id'], $optgroups->current()->firstChild->getAttribute('value'), "valor do terceiro ok 2");

        // SELECT VAZIO!

        $select = $this->Base->setHtmlSelectOption('{title}')->getHtmlSelect($id, 1, array('grouped'=>'artist', 'where'=>array('id'=>100)));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        $this->assertCount(1, $dom->query("#$id"), "id #$id existe");

        $this->assertCount(1, $dom->query("option"), " nenhuma option com where id = 100");
        $this->assertCount(0, $dom->query("optgroup"), " nenhuma optgroup com where id = 100");

        $this->assertEmpty($dom->query("option")->current()->nodeValue, "primeiro é vazio");
        $this->assertEmpty($dom->query("option")->current()->getAttribute('value'), "o valor do primeiro é vazio");
    }

    public function testHtmlSelectMultipleKey()
    {
        // Define a chave multipla
        // como ele deve considerar apenas o primeiro o teste abaixo é o mesmo de testHtmlSelectWhere
        $this->Base->setKey(array('id', 'nao-existo'));

        $id = 'teste';
        $this->Base->setHtmlSelectOption('{title}');

        $select = $this->Base->getHtmlSelect($id, null, array('where'=>array('artist'=>'Rush')));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        $this->assertCount(3, $options, " 3 opções encontradas");

        $this->assertEmpty($options->current()->nodeValue, "primeiro é vazio 1");
        $this->assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio 1");

        $options->next();
        $this->assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 1");
        $this->assertEquals($this->defaultValues[0]['id'], $options->current()->getAttribute('value'), "valor do segundo ok 1");

        $options->next();
        $this->assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 1");
        $this->assertEquals($this->defaultValues[1]['id'], $options->current()->getAttribute('value'), "valor do terceiro ok 1");


        $select = $this->Base->getHtmlSelect($id, 1, array('where'=>array('artist'=>'Rush')));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        $this->assertCount(2, $options, " 2 opções encontradas");

        $this->assertNotEmpty($options->current()->nodeValue, "primeiro não é vazio 2");
        $this->assertNotEmpty($options->current()->getAttribute('value'), "o valor do primeiro não é vazio 2");

        $this->assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 2");
        $this->assertEquals($this->defaultValues[0]['id'], $options->current()->getAttribute('value'), "valor do segundo ok 2");

        $options->next();
        $this->assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 2");
        $this->assertEquals($this->defaultValues[1]['id'], $options->current()->getAttribute('value'), "valor do terceiro ok 2");
    }

    public function testHtmlSelectMultipleKeyWithCast()
    {
        // Define a chave multipla
        // como ele deve considerar apenas o primeiro o teste abaixo é o mesmo de testHtmlSelectWhere
        $this->Base->setKey(array('CAST'=>'id', 'nao-existo'));

        $id = 'teste';
        $this->Base->setHtmlSelectOption('{title}');

        $select = $this->Base->getHtmlSelect($id, null, array('where'=>array('artist'=>'Rush')));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        $this->assertCount(3, $options, " 3 opções encontradas");

        $this->assertEmpty($options->current()->nodeValue, "primeiro é vazio 1");
        $this->assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio 1");

        $options->next();
        $this->assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 1");
        $this->assertEquals($this->defaultValues[0]['id'], $options->current()->getAttribute('value'), "valor do segundo ok 1");

        $options->next();
        $this->assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 1");
        $this->assertEquals($this->defaultValues[1]['id'], $options->current()->getAttribute('value'), "valor do terceiro ok 1");


        $select = $this->Base->getHtmlSelect($id, 1, array('where'=>array('artist'=>'Rush')));
        $this->assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        $this->assertCount(2, $options, " 2 opções encontradas");

        $this->assertNotEmpty($options->current()->nodeValue, "primeiro não é vazio 2");
        $this->assertNotEmpty($options->current()->getAttribute('value'), "o valor do primeiro não é vazio 2");

        $this->assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 2");
        $this->assertEquals($this->defaultValues[0]['id'], $options->current()->getAttribute('value'), "valor do segundo ok 2");

        $options->next();
        $this->assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 2");
        $this->assertEquals($this->defaultValues[1]['id'], $options->current()->getAttribute('value'), "valor do terceiro ok 2");
    }
}
