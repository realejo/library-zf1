<?php
/**
 * RW_Base test case.
 *
 * @category   RW
 * @package    RW_Base
 * @subpackage UnitTests
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */

require_once 'RW/Base.php';

class BaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_Base
     */
    private $RW_Base;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated RW_BaseTest::setUp()
        $this->RW_Base = new RW_Base(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated RW_BaseTest::tearDown()
        $this->RW_Base = null;
        parent::tearDown();
    }
    /**
     * Constructs the test case.
     */
    public function __construct ()
    {
        // TODO Auto-generated constructor
    }
    /**
     * Tests RW_Base::RemoveAcentos()
     */
    public function testRemoveAcentos ()
    {

        $string  = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝŸàáâãäåæçèéêëìíîïñòóôõöùúûüýÿ';
        $retorno = 'AAAAAAAECEEEEIIIINOOOOOUUUUYYaaaaaaaeceeeeiiiinooooouuuuyy';
        $this->assertEquals($retorno, RW_Base::RemoveAcentos($string));
    }
    /**
     * Tests RW_Base::strip_tags_attributes()
     */
    public function testStrip_tags_attributes ()
    {
    	//$allow = '<span><p><ul><li><b><strong><a>';
    	$allow1 = '<span><a><p>';
    	$allowatributs1 = 'style';
    	$str1 = '<p style="text-align:center">Paragraph</p><strong style="color:red">Bold</strong><br/><span style="color:red">Red</span><a href="#">Header</a>';
    	$equals1 = '<p style_-_-"text-align:center">Paragraph</p>Bold<span style_-_-"color:red">Red</span><a href="#">Header</a>';
    	$this->assertEquals($equals1, RW_Base::strip_tags_attributes($str1,$allow1,$allowatributs1));

    	$allow2 = '<span><p><ul><li><b><strong><a>';
    	$allowatributs2 = 'style';
    	$str2 = '<p style="text-align:center">Paragraph</p><strong style="color:red">Bold</strong><br/><span style="color:red">Red</span><a style="color:red" href="#">Header</a>';
    	$equals2 = '<p style_-_-"text-align:center">Paragraph</p><strong style_-_-"color:red">Bold</strong><span style_-_-"color:red">Red</span><a style_-_-"color:red" href="#">Header</a>';
    	$this->assertEquals($equals2, RW_Base::strip_tags_attributes($str2,$allow2,$allowatributs2));
    }
    /**
     * Tests RW_Base::CleanFileName()
     */
    public function testCleanFileName ()
    {
    	$filename = '#$%@#%$ãoçáàbácôíxêchôçú';
        $this->assertEquals('aocaabacoixechocu', RW_Base::CleanFileName($filename));
    }
    /**
     * Tests RW_Base::cleanInput()
     */
    public function testCleanInput ()
    {
        $inputname = '#$%@#%$ãoçáàbácôíxêchôçú';
        $this->assertEquals('obcxch', RW_Base::cleanInput($inputname));
    }
    /**
     * Tests RW_Base::seourl()
     */
    public function testSeourl ()
    {
    	$url 		= 'fazendo uma tremenda bagunça e uma GRANDE confusão';
    	$urlRetorno = 'fazendo-uma-tremenda-bagunca-e-uma-grande-confusao';
        $this->assertEquals($urlRetorno, RW_Base::seourl($url));
    }
    /**
     * Tests RW_Base::getSEOID()
     */
    public function testGetSEOID ()
    {
    	$url1 		= 'http://www.teste.com.br/aqui/-teste';
    	$url2 		= 'http://www.teste.com.br/aqui/,teste';
    	$url3 		= 'http://www.teste.com.br/aqui/,teste-asd';
    	$url4 		= 'http://www.teste.com.br/aqui/-teste,asd';
    	$urlRetorno = 'http://www.teste.com.br/aqui/';
        $this->assertEquals($urlRetorno, RW_Base::getSEOID($url1));
        $this->assertEquals($urlRetorno, RW_Base::getSEOID($url2));
        $this->assertEquals($urlRetorno, RW_Base::getSEOID($url3));
        $this->assertEquals($urlRetorno, RW_Base::getSEOID($url4));
    }
    /**
     * Tests RW_Base::CleanHTML()
     */
    public function testCleanHTML ()
    {
        $allow 	 = '<span><a><br>';
        $str 	 = '<p style="text-align:center">Paragraph</p><strong style="color:red">Bold</strong><br><span style="color:red">Red</span><a href="#">Header</a>';
        $retorno = 'ParagraphBold<br><span style="color:red">Red</span><a href="#">Header</a>';
        $this->assertEquals($retorno, RW_Base::CleanHTML($str,$allow));
    }
    /**
     * Tests RW_Base::getCSV()
     */
    public function testGetCSV ()
    {
        $array[0] = array('nome'=>'Artur dos Santos','idade'=>32,'data_nascimento'=>'28/08/1979','escolaridade'=>'2 Grau');
        $retorno1  = "NOME;IDADE;DATA_NASCIMENTO;ESCOLARIDADE\n\"Artur dos Santos\";\"32\";\"28/08/1979\";\"2 Grau\"";
        $retorno2  = "NOME;IDADE;DATA_NASCIMENTO\n\"Artur dos Santos\";\"32\";\"28/08/1979\"";
        $retorno3  = "NOME;IDADE;DATA_NASCIMENTO\n\"Artur dos Santos\";\"32\";\"28/08/1979\"";
        $this->assertEquals($retorno1, RW_Base::getCSV($array,null));
        $this->assertEquals($retorno2, RW_Base::getCSV($array,'escolaridade'));
        $this->assertEquals($retorno3, RW_Base::getCSV($array,array('escolaridade')));
    }
}

