<?php

declare(strict_types=1);

namespace RWTest\App\Model;

use InvalidArgumentException;
use RW_App_Model_Base;
use RWTest\TestAssets\BaseTestCase;
use Zend_Db_Expr;
use Zend_Dom_Query;
use Zend_Paginator;

/**
 * BaseTest test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class AppModelBaseTest extends BaseTestCase
{
    protected string $tableName = "album";

    protected string $tableKeyName = "id";

    protected $tables = array('album');

    private RW_App_Model_Base $Base;

    protected array $defaultValues = [
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

    public function insertDefaultRows(): self
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
    protected function setUp(): void
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
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->dropTables();

        unset($this->Base);

        $this->clearApplicationData();
    }

    /**
     * Construct sem nome da tabela
     */
    public function testConstructSemTableName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new RW_App_Model_Base(null, $this->tableKeyName);
    }

    /**
     * Construct sem nome da chave
     */
    public function testConstructSemKeyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new RW_App_Model_Base($this->tableName, null);
    }

    /**
     * Definição de chave invalido
     */
    public function testKeyNameInvalido(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->Base->setKey(null);
    }

    /**
     * Definição de ordem invalido
     */
    public function testOrderInvalida(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->Base->setOrder(null);
    }

    /**
     * Definição de ordem invalido
     */
    public function testfetchRowMultiKeyException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        // Cria a tabela com chave string
        $this->Base->setKey(
            array(RW_App_Model_Base::KEY_INTEGER => 'id_int', RW_App_Model_Base::KEY_STRING => 'id_char')
        );
        $this->Base->fetchRow(1);
    }

    /**
     * Definição de chave invalido
     */
    public function testGettersSetters(): void
    {
        self::assertEquals('meuid', $this->Base->setKey('meuid')->getKey());
        self::assertEquals('meuid', $this->Base->setKey('meuid')->getKey(true));
        self::assertEquals('meuid', $this->Base->setKey('meuid')->getKey(false));

        self::assertEquals(array('meuid', 'com array'), $this->Base->setKey(array('meuid', 'com array'))->getKey());
        self::assertEquals(
            array('meuid', 'com array'),
            $this->Base->setKey(array('meuid', 'com array'))->getKey(false)
        );
        self::assertEquals('meuid', $this->Base->setKey(array('meuid', 'com array'))->getKey(true));

        self::assertInstanceOf(
            'Zend_Db_Expr',
            $this->Base->setKey(new Zend_Db_Expr('chave muito exotica!'))->getKey()
        );
        self::assertInstanceOf(
            'Zend_Db_Expr',
            $this->Base->setKey(
                array(new Zend_Db_Expr('chave muito mais exotica!'), 'não existo')
            )->getKey(true)
        );

        self::assertEquals('minhaordem', $this->Base->setOrder('minhaordem')->getOrder());
        self::assertEquals(
            array('minhaordem', 'comarray'),
            $this->Base->setOrder(array('minhaordem', 'comarray'))->getOrder()
        );
        self::assertInstanceOf(
            'Zend_Db_Expr',
            $this->Base->setOrder(new Zend_Db_Expr('ordem muito exotica!'))->getOrder()
        );
    }

    /**
     * Test de criação com a conexão local de testes
     */
    public function testCreateBase(): void
    {
        $Base = new RW_App_Model_Base($this->tableName, $this->tableKeyName);
        self::assertInstanceOf('RW_App_Model_Base', $Base);
        self::assertEquals($this->tableKeyName, $Base->getKey());
        self::assertEquals($this->tableName, $Base->getTable());

        $Base = new RW_App_Model_Base($this->tableName, array($this->tableKeyName, $this->tableKeyName));
        self::assertInstanceOf('RW_App_Model_Base', $Base);
        self::assertEquals(array($this->tableKeyName, $this->tableKeyName), $Base->getKey());
        self::assertEquals($this->tableName, $Base->getTable());

        $Base = new RW_App_Model_Base($this->tableName, $this->tableKeyName);
        self::assertInstanceOf('RW_App_Model_Base', $Base);
        self::assertInstanceOf(
            get_class($this->getAdapter()),
            $Base->getTableGateway()->getAdapter(),
            'tem o Adapter padrão'
        );
        self::assertEquals(
            $this->getAdapter()->getConfig(),
            $Base->getTableGateway()->getAdapter()->getConfig(),
            'tem a mesma configuração do adapter padrão'
        );
    }

    /**
     * Tests Base->getOrder()
     */
    public function testOrder(): void
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
    }

    /**
     * Tests Base->getWhere()
     *
     * Apenas para ter o coverage completo
     */
    public function testWhere(): void
    {
        self::assertEquals(['123456789abcde'], $this->Base->getWhere(['123456789abcde']));
    }

    /**
     * Tests campo deleted
     */
    public function testDeletedField(): void
    {
        // Verifica se deve remover o registro
        $this->Base->setUseDeleted(false);
        self::assertFalse($this->Base->getUseDeleted());
        self::assertTrue($this->Base->setUseDeleted(true)->getUseDeleted());
        self::assertFalse($this->Base->setUseDeleted(false)->getUseDeleted());
        self::assertFalse($this->Base->getUseDeleted());

        // Verifica se deve mostrar o registro
        $this->Base->setShowDeleted(false);
        self::assertFalse($this->Base->getShowDeleted());
        self::assertFalse($this->Base->setShowDeleted(false)->getShowDeleted());
        self::assertTrue($this->Base->setShowDeleted(true)->getShowDeleted());
        self::assertTrue($this->Base->getShowDeleted());
    }

    public function testGetSQlString(): void
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
    }

    public function testGetSQlSelect(): void
    {
        $select = $this->Base->getTableSelect();
        self::assertInstanceOf('Zend_Db_Table_Select', $select);
        self::assertEquals($select->assemble(), $this->Base->getSQLString());
    }

    public function testFetchAll(): void
    {
        // O padrão é não usar o campo deleted
        $albuns = $this->Base->fetchAll();
        self::assertCount(4, $albuns, 'showDeleted=false, useDeleted=false');

        // Marca para mostrar os removidos e não usar o campo deleted
        $this->Base->setShowDeleted(true)->setUseDeleted(false);
        self::assertCount(4, $this->Base->fetchAll(), 'showDeleted=true, useDeleted=false');

        // Marca pra não mostar os removidos e usar o campo deleted
        $this->Base->setShowDeleted(false)->setUseDeleted(true);
        self::assertCount(3, $this->Base->fetchAll(), 'showDeleted=false, useDeleted=true');

        // Marca pra mostrar os removidos e usar o campo deleted
        $this->Base->setShowDeleted(true)->setUseDeleted(true);
        $albuns = $this->Base->fetchAll();
        self::assertCount(4, $albuns, 'showDeleted=true, useDeleted=true');

        // Marca não mostrar os removios
        $this->Base->setUseDeleted(true)->setShowDeleted(false);

        $albuns = $this->defaultValues;
        unset($albuns[3]); // remove o deleted=1
        self::assertEquals($albuns, $this->Base->fetchAll());

        // Marca mostrar os removios
        $this->Base->setShowDeleted(true);

        self::assertEquals($this->defaultValues, $this->Base->fetchAll());
        self::assertCount(4, $this->Base->fetchAll());
        $this->Base->setShowDeleted(false);
        self::assertCount(3, $this->Base->fetchAll());

        // Verifica o where
        self::assertCount(2, $this->Base->fetchAll(['artist' => $albuns[0]['artist']]));
        self::assertNull($this->Base->fetchAll(['artist' => $this->defaultValues[3]['artist']]));

        // Verifica o paginator com o padrão
        $paginator = $this->Base->setUsePaginator(true)->fetchAll();
        self::assertInstanceOf(Zend_Paginator::class, $paginator);
        $paginator = $paginator->toJson();

        // Tem um bug no Zend_Paginator
        //http://framework.zend.com/issues/browse/ZF-9731
        $paginator = (array)json_decode($paginator);
        $temp = array();
        foreach ($paginator as $p) {
            $temp[] = $p;
        }
        $paginator = json_encode($temp);

        $fetchAll = $this->Base->setUsePaginator(false)->fetchAll();
        self::assertNotEquals(json_encode($this->defaultValues), $paginator);
        self::assertEquals(json_encode($fetchAll), $paginator, 'retorno do paginator é igual');

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
        foreach ($paginator as $p) {
            $temp[] = $p;
        }
        $paginator = json_encode($temp);

        self::assertNotEquals(json_encode($this->defaultValues), $paginator);
        $fetchAll = $this->Base->setUsePaginator(false)->fetchAll(null, null, 2);
        self::assertEquals(json_encode($fetchAll), $paginator);

        // Apaga qualquer cache
        self::assertTrue($this->Base->getCache()->clean(), 'apaga o cache');

        // Define exibir os deletados
        $this->Base->setShowDeleted(true);

        // Liga o cache
        $this->Base->setUseCache(true);
        self::assertEquals($this->defaultValues, $this->Base->fetchAll(), 'fetchAll está igual ao defaultValues');
        self::assertCount(4, $this->Base->fetchAll(), 'Deve conter 4 registros');

        // Grava um registro "sem o cache saber"
        $this->Base->getTableGateway()->insert(
            array('id' => 10, 'artist' => 'nao existo por enquanto', 'title' => 'bla bla', 'deleted' => 0)
        );

        self::assertCount(4, $this->Base->fetchAll(), 'Deve conter 4 registros depois do insert "sem o cache saber"');
        self::assertTrue($this->Base->getCache()->clean(), 'limpa o cache');
        self::assertCount(5, $this->Base->fetchAll(), 'Deve conter 5 registros');

        // Define não exibir os deletados
        $this->Base->setShowDeleted(false);
        self::assertCount(4, $this->Base->fetchAll(), 'Deve conter 4 registros com showDeleted=false');

        // Apaga um registro "sem o cache saber"
        $this->Base->getTableGateway()->delete("id=10");
        $this->Base->setShowDeleted(true);
        self::assertCount(5, $this->Base->fetchAll(), 'Deve conter 5 registros');
        self::assertTrue($this->Base->getCache()->clean(), 'apaga o cache');
        self::assertCount(4, $this->Base->fetchAll(), 'Deve conter 4 registros 4');
    }

    public function testFetchRow(): void
    {
        // Marca pra usar o campo deleted
        $this->Base->setUseDeleted(true);

        // Verifica os itens que existem
        self::assertEquals($this->defaultValues[0], $this->Base->fetchRow(1));
        self::assertEquals($this->defaultValues[1], $this->Base->fetchRow(2));
        self::assertEquals($this->defaultValues[2], $this->Base->fetchRow(3));

        // Verifica o item removido
        $this->Base->setShowDeleted(true);
        self::assertEquals($this->defaultValues[3], $this->Base->fetchRow(4));
        $this->Base->setShowDeleted(false);
    }

    public function testFetchRowWithIntegerKey(): void
    {
        $this->Base->setKey(array(RW_App_Model_Base::KEY_INTEGER => 'id'));

        // Marca pra usar o campo deleted
        $this->Base->setUseDeleted(true);

        // Verifica os itens que existem
        self::assertEquals($this->defaultValues[0], $this->Base->fetchRow(1));
        self::assertEquals($this->defaultValues[1], $this->Base->fetchRow(2));
        self::assertEquals($this->defaultValues[2], $this->Base->fetchRow(3));

        // Verifica o item removido
        $this->Base->setShowDeleted(true);
        self::assertEquals($this->defaultValues[3], $this->Base->fetchRow(4));
        $this->Base->setShowDeleted(false);
    }

    public function testFetchRowWithStringKey(): void
    {
        $this->dropTables()->createTables(array('album_string'));
        $defaultValues = [
            [
                'id' => 'A',
                'artist' => 'Rush',
                'title' => 'Rush',
                'deleted' => 0
            ],
            [
                'id' => 'B',
                'artist' => 'Rush',
                'title' => 'Moving Pictures',
                'deleted' => 0
            ],
            [
                'id' => 'C',
                'artist' => 'Dream Theater',
                'title' => 'Images And Words',
                'deleted' => 0
            ],
            [
                'id' => 'D',
                'artist' => 'Claudia Leitte',
                'title' => 'Exttravasa',
                'deleted' => 1
            ]
        ];
        foreach ($defaultValues as $row) {
            $this->getAdapter()->query(
                "INSERT into {$this->tableName}({$this->tableKeyName}, artist, title, deleted)
                                        VALUES (
                                        '{$row['id']}',
                                        '{$row['artist']}',
                                        '{$row['title']}',
                                        {$row['deleted']}
                                        );"
            );
        }

        $this->Base->setKey(array(RW_App_Model_Base::KEY_STRING => 'id'));

        // Marca pra usar o campo deleted
        $this->Base->setUseDeleted(true);

        // Verifica os itens que existem
        self::assertEquals($defaultValues[0], $this->Base->fetchRow('A'));
        self::assertEquals($defaultValues[1], $this->Base->fetchRow('B'));
        self::assertEquals($defaultValues[2], $this->Base->fetchRow('C'));

        // Verifica o item removido
        $this->Base->setShowDeleted(true);
        self::assertEquals($defaultValues[3], $this->Base->fetchRow('D'));
        $this->Base->setShowDeleted(false);
    }

    public function testFetchRowWithMultipleKey(): void
    {
        $this->dropTables()->createTables(array('album_array'));
        $defaultValues = [
            [
                'id_int' => 1,
                'id_char' => 'A',
                'artist' => 'Rush',
                'title' => 'Rush',
                'deleted' => 0
            ],
            [
                'id_int' => 2,
                'id_char' => 'B',
                'artist' => 'Rush',
                'title' => 'Moving Pictures',
                'deleted' => 0
            ],
            [
                'id_int' => 3,
                'id_char' => 'C',
                'artist' => 'Dream Theater',
                'title' => 'Images And Words',
                'deleted' => 0
            ],
            [
                'id_int' => 4,
                'id_char' => 'D',
                'artist' => 'Claudia Leitte',
                'title' => 'Exttravasa',
                'deleted' => 1
            ]
        ];
        foreach ($defaultValues as $row) {
            $this->getAdapter()->query(
                "INSERT into album (id_int, id_char, artist, title, deleted)
                                        VALUES (
                                        '{$row['id_int']}',
                                        '{$row['id_char']}',
                                        '{$row['artist']}',
                                        '{$row['title']}',
                                        {$row['deleted']}
                                        );"
            );
        }

        $this->Base->setKey(array(RW_App_Model_Base::KEY_STRING => 'id'));

        // Marca pra usar o campo deleted
        $this->Base->setUseDeleted(true);

        // Verifica os itens que existem
        self::assertEquals($defaultValues[0], $this->Base->fetchRow(array('id_char' => 'A', 'id_int' => 1)));
        self::assertEquals($defaultValues[1], $this->Base->fetchRow(array('id_char' => 'B', 'id_int' => 2)));
        self::assertEquals($defaultValues[2], $this->Base->fetchRow(array('id_char' => 'C', 'id_int' => 3)));

        self::assertNull($this->Base->fetchRow(array('id_char' => 'C', 'id_int' => 2)));

        // Verifica o item removido
        $this->Base->setShowDeleted(true);
        self::assertEquals($defaultValues[3], $this->Base->fetchRow(array('id_char' => 'D', 'id_int' => 4)));
        $this->Base->setShowDeleted(false);
    }

    public function testFetchAssoc(): void
    {
        // O padrão é não usar o campo deleted
        $albuns = $this->Base->fetchAssoc();
        self::assertCount(4, $albuns, 'showDeleted=false, useDeleted=false');
        self::assertEquals($this->defaultValues[0], $albuns[1]);
        self::assertEquals($this->defaultValues[1], $albuns[2]);
        self::assertEquals($this->defaultValues[2], $albuns[3]);
        self::assertEquals($this->defaultValues[3], $albuns[4]);

        // Marca para mostrar os removidos e não usar o campo deleted
        $this->Base->setShowDeleted(true)->setUseDeleted(false);
        self::assertCount(4, $this->Base->fetchAssoc(), 'showDeleted=true, useDeleted=false');

        // Marca pra mostrar os removidos e usar o campo deleted
        $this->Base->setShowDeleted(true)->setUseDeleted(true);
        $albuns = $this->Base->fetchAssoc();
        self::assertCount(4, $albuns, 'showDeleted=true, useDeleted=true');
        self::assertEquals($this->defaultValues[0], $albuns[1]);
        self::assertEquals($this->defaultValues[1], $albuns[2]);
        self::assertEquals($this->defaultValues[2], $albuns[3]);
        self::assertEquals($this->defaultValues[3], $albuns[4]);
    }

    public function testFetchAssocWithMultipleKeys(): void
    {
        $this->Base->setKey(array($this->tableKeyName, 'naoexisto'));

        // O padrão é não usar o campo deleted
        $albuns = $this->Base->fetchAssoc();
        self::assertCount(4, $albuns, 'showDeleted=false, useDeleted=false');
        self::assertEquals($this->defaultValues[0], $albuns[1]);
        self::assertEquals($this->defaultValues[1], $albuns[2]);
        self::assertEquals($this->defaultValues[2], $albuns[3]);
        self::assertEquals($this->defaultValues[3], $albuns[4]);

        // Marca para mostrar os removidos e não usar o campo deleted
        $this->Base->setShowDeleted(true)->setUseDeleted(false);
        self::assertCount(4, $this->Base->fetchAssoc(), 'showDeleted=true, useDeleted=false');

        // Marca pra mostrar os removidos e usar o campo deleted
        $this->Base->setShowDeleted(true)->setUseDeleted(true);
        $albuns = $this->Base->fetchAssoc();
        self::assertCount(4, $albuns, 'showDeleted=true, useDeleted=true');
        self::assertEquals($this->defaultValues[0], $albuns[1]);
        self::assertEquals($this->defaultValues[1], $albuns[2]);
        self::assertEquals($this->defaultValues[2], $albuns[3]);
        self::assertEquals($this->defaultValues[3], $albuns[4]);
    }

    public function testHtmlSelectGettersSetters(): void
    {
        self::assertEquals('{nome}', $this->Base->getHtmlSelectOption(), 'padrão {nome}');
        self::assertInstanceOf(
            'RW_App_Model_Base',
            $this->Base->setHtmlSelectOption('{title}'),
            'setHtmlSelectOption() retorna RW_App_Model_Base'
        );
        self::assertEquals('{title}', $this->Base->getHtmlSelectOption(), 'troquei por {title}');
    }

    public function testHtmlSelectWhere(): void
    {
        $id = 'teste';
        $this->Base->setHtmlSelectOption('{title}');

        $select = $this->Base->getHtmlSelect($id, null, array('where' => array('artist' => 'Rush')));
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        self::assertCount(3, $options, " 3 opções encontradas");

        self::assertEmpty($options->current()->nodeValue, "primeiro é vazio 1");
        self::assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio 1");

        $options->next();
        self::assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 1");
        self::assertEquals(
            $this->defaultValues[0]['id'],
            $options->current()->getAttribute('value'),
            "valor do segundo ok 1"
        );

        $options->next();
        self::assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 1");
        self::assertEquals(
            $this->defaultValues[1]['id'],
            $options->current()->getAttribute('value'),
            "valor do terceiro ok 1"
        );


        $select = $this->Base->getHtmlSelect($id, '1', ['where' => ['artist' => 'Rush']]);
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        self::assertCount(2, $options, " 2 opções encontradas");

        self::assertNotEmpty($options->current()->nodeValue, "primeiro não é vazio 2");
        self::assertNotEmpty($options->current()->getAttribute('value'), "o valor do primeiro não é vazio 2");

        self::assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 2");
        self::assertEquals(
            $this->defaultValues[0]['id'],
            $options->current()->getAttribute('value'),
            "valor do segundo ok 2"
        );

        $options->next();
        self::assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 2");
        self::assertEquals(
            $this->defaultValues[1]['id'],
            $options->current()->getAttribute('value'),
            "valor do terceiro ok 2"
        );
    }

    public function testHtmlSelectSemOptionValido(): void
    {
        $id = 'teste';

        $select = $this->Base->getHtmlSelect($id);
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        self::assertCount(5, $options, " 5 opções encontradas");

        self::assertEmpty($options->current()->nodeValue, "primeiro é vazio 1");
        self::assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio 1");

        $options->next();
        self::assertEmpty($options->current()->nodeValue, "nome do segundo ok 1");
        self::assertEquals(
            $this->defaultValues[0]['id'],
            $options->current()->getAttribute('value'),
            "valor do segundo ok 1"
        );

        $options->next();
        self::assertEmpty($options->current()->nodeValue, "nome do terceiro ok 1");
        self::assertEquals(
            $this->defaultValues[1]['id'],
            $options->current()->getAttribute('value'),
            "valor do terceiro ok 1"
        );

        $options->next();
        self::assertEmpty($options->current()->nodeValue, "nome do quarto ok 1");
        self::assertEquals(
            $this->defaultValues[2]['id'],
            $options->current()->getAttribute('value'),
            "valor do quarto ok 1"
        );

        $options->next();
        self::assertEmpty($options->current()->nodeValue, "nome do quinto ok 1");
        self::assertEquals(
            $this->defaultValues[3]['id'],
            $options->current()->getAttribute('value'),
            "valor do quinto ok 1"
        );

        $select = $this->Base->setHtmlSelectOption('{nao_existo}')->getHtmlSelect($id);
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        self::assertCount(5, $options, " 5 opções encontradas");

        self::assertEmpty($options->current()->nodeValue, "primeiro é vazio 2");
        self::assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio 2");

        $options->next();
        self::assertEmpty($options->current()->nodeValue, "nome do segundo ok 2");
        self::assertEquals(
            $this->defaultValues[0]['id'],
            $options->current()->getAttribute('value'),
            "valor do segundo ok 2"
        );

        $options->next();
        self::assertEmpty($options->current()->nodeValue, "nome do terceiro ok 2");
        self::assertEquals(
            $this->defaultValues[1]['id'],
            $options->current()->getAttribute('value'),
            "valor do terceiro ok 2"
        );

        $options->next();
        self::assertEmpty($options->current()->nodeValue, "nome do quarto ok 2");
        self::assertEquals(
            $this->defaultValues[2]['id'],
            $options->current()->getAttribute('value'),
            "valor do quarto ok 2"
        );

        $options->next();
        self::assertEmpty($options->current()->nodeValue, "nome do quinto ok 2");
        self::assertEquals(
            $this->defaultValues[3]['id'],
            $options->current()->getAttribute('value'),
            "valor do quinto ok 2"
        );
    }

    public function testHtmlSelectOption(): void
    {
        $id = 'teste';

        $select = $this->Base->setHtmlSelectOption('{artist}')->getHtmlSelect($id);
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        self::assertCount(1, $dom->query("#$id"), "id #$id existe");
        self::assertCount(1, $dom->query("select[name=\"$id\"]"), "placeholder select[name=\"$id\"] encontrado");
        $options = $dom->query("option");
        self::assertCount(5, $options, " 5 opções encontradas");

        self::assertEmpty($options->current()->nodeValue, "primeiro é vazio");
        self::assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio");

        $options->next();
        self::assertEquals($this->defaultValues[0]['artist'], $options->current()->nodeValue, "nome do segundo ok");
        self::assertEquals(
            $this->defaultValues[0]['id'],
            $options->current()->getAttribute('value'),
            "valor do segundo ok"
        );

        $options->next();
        self::assertEquals($this->defaultValues[1]['artist'], $options->current()->nodeValue, "nome do terceiro ok");
        self::assertEquals(
            $this->defaultValues[1]['id'],
            $options->current()->getAttribute('value'),
            "valor do terceiro ok"
        );

        $options->next();
        self::assertEquals($this->defaultValues[2]['artist'], $options->current()->nodeValue, "nome do quarto ok");
        self::assertEquals(
            $this->defaultValues[2]['id'],
            $options->current()->getAttribute('value'),
            "valor do quarto ok"
        );

        $options->next();
        self::assertEquals($this->defaultValues[3]['artist'], $options->current()->nodeValue, "nome do quinto ok");
        self::assertEquals(
            $this->defaultValues[3]['id'],
            $options->current()->getAttribute('value'),
            "valor do quinto ok"
        );
    }

    public function testHtmlSelectPlaceholder(): void
    {
        $ph = 'myplaceholder';
        $select = $this->Base->getHtmlSelect('nome_usado', null, array('placeholder' => $ph));
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        self::assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe');
        self::assertCount(
            1,
            $dom->query("select[placeholder=\"$ph\"]"),
            "placeholder select[placeholder=\"$ph\"] encontrado"
        );
        $options = $dom->query("option");
        self::assertCount(5, $options, " 5 opções encontradas");
        self::assertEquals($ph, $options->current()->nodeValue, "placeholder é a primeira");
        self::assertEmpty($options->current()->getAttribute('value'), "o valor do placeholder é vazio");
    }

    public function testHtmlSelectShowEmpty(): void
    {
        $select = $this->Base->getHtmlSelect('nome_usado');
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        self::assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe');
        self::assertCount(5, $dom->query('option'), '5 opções existem');
        self::assertEmpty($dom->query('option')->current()->nodeValue, "a primeira é vazia");
        self::assertEmpty($dom->query('option')->current()->getAttribute('value'), "o valor da primeira é vazio");

        $select = $this->Base->getHtmlSelect('nome_usado', '1');
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        self::assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe COM valor padrão');
        self::assertCount(4, $dom->query('option'), '4 opções existem COM valor padrão');

        $select = $this->Base->getHtmlSelect('nome_usado', null, array('show-empty' => false));
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        self::assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe SEM valor padrão e show-empty=false');
        self::assertCount(4, $dom->query('option'), '4 opções existem SEM valor padrão e show-empty=false');

        // sem mostrar o empty
        $select = $this->Base->getHtmlSelect('nome_usado', '1', array('show-empty' => false));
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        self::assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe com valor padrão e show-empty=false');
        self::assertCount(4, $dom->query('option'), '4 opções existem com valor padrão e show-empty=false');

        // sem mostrar o empty
        $select = $this->Base->getHtmlSelect('nome_usado', '1', array('show-empty' => true));
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        self::assertCount(1, $dom->query('#nome_usado'), 'id #nome_usado existe com valor padrão e show-empty=true');
        self::assertCount(5, $dom->query('option'), '5 opções existem com valor padrão e show-empty=true');
        self::assertEmpty(
            $dom->query('option')->current()->nodeValue,
            "a primeira é vazia com valor padrão e show-empty=true"
        );
        self::assertEmpty(
            $dom->query('option')->current()->getAttribute('value'),
            "o valor da primeira é vazio com valor padrão e show-empty=true"
        );
    }

    public function testHtmlSelectGrouped(): void
    {
        $id = 'teste';

        $select = $this->Base->setHtmlSelectOption('{title}')
            ->getHtmlSelect($id, '1', ['grouped' => 'artist']);
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        self::assertCount(1, $dom->query("#$id"), "id #$id existe");

        $options = $dom->query("option");
        self::assertCount(4, $options, " 4 opções encontradas");

        self::assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do primeiro ok 1");
        self::assertEquals(
            $this->defaultValues[0]['id'],
            $options->current()->getAttribute('value'),
            "valor do primeiro ok 1"
        );

        $options->next();
        self::assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do segundo ok 1");
        self::assertEquals(
            $this->defaultValues[1]['id'],
            $options->current()->getAttribute('value'),
            "valor do segundo ok 1"
        );

        $options->next();
        self::assertEquals($this->defaultValues[2]['title'], $options->current()->nodeValue, "nome do terceiro ok 1");
        self::assertEquals(
            $this->defaultValues[2]['id'],
            $options->current()->getAttribute('value'),
            "valor do terceiro ok 1"
        );

        $options->next();
        self::assertEquals($this->defaultValues[3]['title'], $options->current()->nodeValue, "nome do quarto ok 1");
        self::assertEquals(
            $this->defaultValues[3]['id'],
            $options->current()->getAttribute('value'),
            "valor do quarto ok 1"
        );

        $optgroups = $dom->query("optgroup");
        self::assertCount(3, $optgroups, " 3 grupo de opções encontrados");

        self::assertEquals(
            $this->defaultValues[0]['artist'],
            $optgroups->current()->getAttribute('label'),
            "nome do primeiro grupo ok"
        );
        self::assertEquals(2, $optgroups->current()->childNodes->length, " 2 opções encontrados no priemiro optgroup");
        self::assertEquals(
            $this->defaultValues[0]['title'],
            $optgroups->current()->firstChild->nodeValue,
            "nome do primeiro ok 2"
        );
        self::assertEquals(
            $this->defaultValues[0]['id'],
            $optgroups->current()->firstChild->getAttribute('value'),
            "valor do primeiro ok 2"
        );
        self::assertEquals(
            $this->defaultValues[1]['title'],
            $optgroups->current()->firstChild->nextSibling->nodeValue,
            "nome do segundo ok 2"
        );
        self::assertEquals(
            $this->defaultValues[1]['id'],
            $optgroups->current()->firstChild->nextSibling->getAttribute('value'),
            "valor do segundo ok 2"
        );

        $optgroups->next();
        self::assertEquals(
            $this->defaultValues[2]['artist'],
            $optgroups->current()->getAttribute('label'),
            "nome do segundo grupo ok"
        );
        self::assertEquals(1, $optgroups->current()->childNodes->length, " 2 opções encontrados");
        self::assertEquals(
            $this->defaultValues[2]['title'],
            $optgroups->current()->firstChild->nodeValue,
            "nome do terceiro ok 2"
        );
        self::assertEquals(
            $this->defaultValues[2]['id'],
            $optgroups->current()->firstChild->getAttribute('value'),
            "valor do terceiro ok 2"
        );

        $optgroups->next();
        self::assertEquals(
            $this->defaultValues[3]['artist'],
            $optgroups->current()->getAttribute('label'),
            "nome do terceiro grupo ok"
        );
        self::assertEquals(1, $optgroups->current()->childNodes->length, " 2 opções encontrados");
        self::assertEquals(
            $this->defaultValues[3]['title'],
            $optgroups->current()->firstChild->nodeValue,
            "nome do terceiro ok 2"
        );
        self::assertEquals(
            $this->defaultValues[3]['id'],
            $optgroups->current()->firstChild->getAttribute('value'),
            "valor do terceiro ok 2"
        );

        // SELECT VAZIO!

        $select = $this->Base->setHtmlSelectOption('{title}')->getHtmlSelect(
            $id,
            '1',
            [
                'grouped' => 'artist',
                'where' => ['id' => 100]
            ]
        );
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);
        self::assertCount(1, $dom->query("#$id"), "id #$id existe");

        self::assertCount(1, $dom->query("option"), " nenhuma option com where id = 100");
        self::assertCount(0, $dom->query("optgroup"), " nenhuma optgroup com where id = 100");

        self::assertEmpty($dom->query("option")->current()->nodeValue, "primeiro é vazio");
        self::assertEmpty($dom->query("option")->current()->getAttribute('value'), "o valor do primeiro é vazio");
    }

    public function testHtmlSelectMultipleKey(): void
    {
        // Define a chave multipla
        // como ele deve considerar apenas o primeiro o teste abaixo é o mesmo de testHtmlSelectWhere
        $this->Base->setKey(array('id', 'nao-existo'));

        $id = 'teste';
        $this->Base->setHtmlSelectOption('{title}');

        $select = $this->Base->getHtmlSelect($id, null, array('where' => array('artist' => 'Rush')));
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        self::assertCount(3, $options, " 3 opções encontradas");

        self::assertEmpty($options->current()->nodeValue, "primeiro é vazio 1");
        self::assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio 1");

        $options->next();
        self::assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 1");
        self::assertEquals(
            $this->defaultValues[0]['id'],
            $options->current()->getAttribute('value'),
            "valor do segundo ok 1"
        );

        $options->next();
        self::assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 1");
        self::assertEquals(
            $this->defaultValues[1]['id'],
            $options->current()->getAttribute('value'),
            "valor do terceiro ok 1"
        );

        $select = $this->Base->getHtmlSelect($id, '1', ['where' => ['artist' => 'Rush']]);
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        self::assertCount(2, $options, " 2 opções encontradas");

        self::assertNotEmpty($options->current()->nodeValue, "primeiro não é vazio 2");
        self::assertNotEmpty($options->current()->getAttribute('value'), "o valor do primeiro não é vazio 2");

        self::assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 2");
        self::assertEquals(
            $this->defaultValues[0]['id'],
            $options->current()->getAttribute('value'),
            "valor do segundo ok 2"
        );

        $options->next();
        self::assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 2");
        self::assertEquals(
            $this->defaultValues[1]['id'],
            $options->current()->getAttribute('value'),
            "valor do terceiro ok 2"
        );
    }

    public function testHtmlSelectMultipleKeyWithCast(): void
    {
        // Define a chave multipla
        // como ele deve considerar apenas o primeiro o teste abaixo é o mesmo de testHtmlSelectWhere
        $this->Base->setKey(['CAST' => 'id', 'nao-existo']);

        $id = 'teste';
        $this->Base->setHtmlSelectOption('{title}');

        $select = $this->Base->getHtmlSelect($id, null, ['where' => ['artist' => 'Rush']]);
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        self::assertCount(3, $options, " 3 opções encontradas");

        self::assertEmpty($options->current()->nodeValue, "primeiro é vazio 1");
        self::assertEmpty($options->current()->getAttribute('value'), "o valor do primeiro é vazio 1");

        $options->next();
        self::assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 1");
        self::assertEquals(
            $this->defaultValues[0]['id'],
            $options->current()->getAttribute('value'),
            "valor do segundo ok 1"
        );

        $options->next();
        self::assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 1");
        self::assertEquals(
            $this->defaultValues[1]['id'],
            $options->current()->getAttribute('value'),
            "valor do terceiro ok 1"
        );


        $select = $this->Base->getHtmlSelect($id, '1', ['where' => ['artist' => 'Rush']]);
        self::assertNotEmpty($select);
        $dom = new Zend_Dom_Query($select);

        $options = $dom->query("option");
        self::assertCount(2, $options, " 2 opções encontradas");

        self::assertNotEmpty($options->current()->nodeValue, "primeiro não é vazio 2");
        self::assertNotEmpty($options->current()->getAttribute('value'), "o valor do primeiro não é vazio 2");

        self::assertEquals($this->defaultValues[0]['title'], $options->current()->nodeValue, "nome do segundo ok 2");
        self::assertEquals(
            $this->defaultValues[0]['id'],
            $options->current()->getAttribute('value'),
            "valor do segundo ok 2"
        );

        $options->next();
        self::assertEquals($this->defaultValues[1]['title'], $options->current()->nodeValue, "nome do terceiro ok 2");
        self::assertEquals(
            $this->defaultValues[1]['id'],
            $options->current()->getAttribute('value'),
            "valor do terceiro ok 2"
        );
    }
}
