<?php
/**
 * RW_Search test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class SearchTest extends BaseTestCase
{

    /**
     * @var RW_Search
     */
    private $RW_Search;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        parent::tearDown();
    }

    /**
     * getCachePath sem nome da pasta
     *
     * @expectedException Exception
     */
    public function testGetIndexRootSemAPPLICATION_DATA()
    {
        if (defined("APPLICATION_DATA")) {
            throw new Exception('APPLICATION_DATA já foi definido em outro lugar');
        }

        RW_Search::getIndexRoot();
    }

    /**
     * getCachePath sem nome da pasta
     */
    public function testGetIndexRoot()
    {
        $this->setApplicationConstants()->clearApplicationData();

        // Recupera a pasta aonde será salva as informações
        $path = RW_Search::getIndexRoot();

        // Verifica se tere o retorno correto
        $this->assertNotNull($path, 'a path foi retornado');
        $this->assertTrue(is_dir($path), "$path é um diretório");
        $this->assertTrue(is_writable($path), 'tem permissão de escrita');

        $this->clearApplicationData();
    }

    /**
     * Constructs the test case.
     *
     * @expectedException Exception
     */
    public function testGetIndexSemPath()
    {
        RW_Search::getIndex(/* SEM PATH */);
    }

    /**
     * Tests RW_Search::getIndex()
     */
    public function testGetIndex()
    {
        $this->markTestIncomplete("testGetIndex test not implemented");
        $this->assertInstanceOf('Zend_Search_Lucene_Interface', RW_Search::getIndex('test'));
    }

    /**
     * Tests RW_Search::resumoHighlight()
     */
    public function testResumoHighlight()
    {
        // TODO Auto-generated SearchTest::testResumoHighlight()
        $this->markTestIncomplete("resumoHighlight test not implemented");
        RW_Search::resumoHighlight(/* parameters */);
    }

    /**
     * Tests RW_Search::fixHighlight()
     */
    public function testFixHighlight()
    {
        // TODO Auto-generated SearchTest::testFixHighlight()
        $this->markTestIncomplete("fixHighlight test not implemented");
        RW_Search::fixHighlight(/* parameters */);
    }

    /**
     * Tests RW_Search::simples()
     */
    public function testSimples()
    {
        // TODO Auto-generated SearchTest::testSimples()
        $this->markTestIncomplete("simples test not implemented");
        RW_Search::simples(/* parameters */);
    }
}

