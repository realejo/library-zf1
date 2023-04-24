<?php

declare(strict_types=1);

namespace RWTest;

use RW_Base;

/**
 * RW_Base test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class BaseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RW_Base
     */
    private $RW_Base;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->RW_Base = new RW_Base();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        $this->RW_Base = null;

        parent::tearDown();
    }

    /**
     * Tests RW_Base::RemoveAcentos()
     */
    public function testRemoveAcentos()
    {

        $string  = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝŸàáâãäåæçèéêëìíîïñòóôõöùúûüýÿ';
        $retorno = 'AAAAAAAECEEEEIIIINOOOOOUUUUYYaaaaaaaeceeeeiiiinooooouuuuyy';
        $this->assertEquals($retorno, RW_Base::RemoveAcentos($string));
    }

    /**
     * Tests RW_Base::strip_tags_attributes()
     */
    public function testStrip_tags_attributes()
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
    public function testCleanFileName()
    {
    	$filename = '#$%@#%$ãoçáàbácôíxêchôçú';
        $this->assertEquals('aocaabacoixechocu', RW_Base::CleanFileName($filename));
    }

    /**
     * Tests RW_Base::testSanitize()
     */
    public function testSanitize()
    {
        $this->assertEquals("uma frase com aáeéiíoóuú e espaços",$this->RW_Base->sanitize("uma frase com aáeéiíoóuú e espaços"));
        $this->assertEquals("áéíóú123",$this->RW_Base->sanitize("áéíóú123"));
        $this->assertEquals("áéíóú123",$this->RW_Base->sanitize("áéíóú123\n"));

        // Array
        $teste = array('linha1'=>"áéíóú123-", 'linha2'=> "áéíóú123\n", 'linha3'=> "áéíóú123\n");

        $resultado = array('linha1'=>"áéíóú123-", 'linha2'=> "áéíóú123", 'linha3'=> "áéíóú123");
        $this->assertEquals($resultado,$this->RW_Base->sanitize($teste));

        $resultado = array('linha1'=>"áéíóú123-", 'linha2'=> "áéíóú123\n", 'linha3'=> "áéíóú123");
        $this->assertEquals($resultado,$this->RW_Base->sanitize($teste,array('ignore'=>'linha2')));

        // Nomes com orkutify
        $this->assertEquals('Ana P.',$this->RW_Base->sanitize('Ana ▒ ▒ ▒ P.'));
        $this->assertEquals('Luiz M.',$this->RW_Base->sanitize('Luiz M. ♪♫'));
        $this->assertEquals('Luiz áéúíó M.',$this->RW_Base->sanitize('Luiz áéúíó M. ♪♫'));
        $this->assertEquals('Thamyris Mendonça',$this->RW_Base->sanitize('•●๋• Thamyris Mendonça •●๋•'));
        $this->assertEquals('FERNANDA FIGHT',$this->RW_Base->sanitize('☠ FERNANDA FIGHT ☠ '));

        // URL
        $this->assertEquals('',$this->RW_Base->sanitize('áéúí', array('url')));

        // Especiais
        $this->assertEquals('',$this->RW_Base->sanitize(null));
        $this->assertEquals('',$this->RW_Base->sanitize("\n"));

        // Caracteres escondidos ou inválidos
        $this->assertEquals("bigbob !",$this->RW_Base->sanitize("bigbob ­­ !")); // não é hifen!
        $this->assertEquals("bigbob!",$this->RW_Base->sanitize("bigbob­­!")); // não é hifen!
        $this->assertEquals("!!",$this->RW_Base->sanitize("!­­!")); // não é hifen!
        $this->assertEquals("!--!",$this->RW_Base->sanitize("!--!")); // é hifen!
    }

    /**
     * Tests RW_Base::seourl()
     */
    public function testSeourl()
    {
    	$url 		= 'fazendo uma tremenda bagunça e uma GRANDE confusão';
    	$urlRetorno = 'fazendo-uma-tremenda-bagunca-e-uma-grande-confusao';
        $this->assertEquals($urlRetorno, RW_Base::seourl($url));

        $url 		= utf8_decode('fazendo uma tremenda bagunça e uma GRANDE confusão');
    	$urlRetorno = 'fazendo-uma-tremenda-baguna-e-uma-grande-confuso';
        $this->assertEquals($urlRetorno, RW_Base::seourl($url));
    }

    /**
     * Tests RW_Base::getSafeSEO()
     */
    public function testGetSafeSEO()
    {
        $this->assertEquals('123-bla,blsdsa-bla', $this->RW_Base->getSafeSEO(' 123-bla,blsds   a-bla '));
    }

    /**
     * Tests RW_Base::getSafeID()
     */
    public function testGetSafeID()
    {
        $this->assertEquals('123blablsdsabla', $this->RW_Base->getSafeID('123-bla,blsds   a-bla '));
    }

    /**
     * Tests RW_Base::getSEOID()
     */
    public function testGetSEOID()
    {
        $this->assertEquals('123', $this->RW_Base->getSEOID('123-bla,bla-bla'));
        $this->assertEquals('123', $this->RW_Base->getSEOID('123,bla-bla-bla'));

        $this->assertEquals('123', $this->RW_Base->getSEOID('123,bla'));
        $this->assertEquals('123', $this->RW_Base->getSEOID('123-bla'));

        $this->assertEquals('123', $this->RW_Base->getSEOID('123-bla-bla-bla', '-'));
        $this->assertEquals('123-bla-bla-bla', $this->RW_Base->getSEOID('123-bla-bla-bla', ','));
        $this->assertEquals('123-bla', $this->RW_Base->getSEOID('123-bla,bla-bla',','));

        $this->assertEquals('agora', $this->RW_Base->getSEOID('ágora-sim', '-'));
    }

    /**
     * Tests RW_Base::CleanHTML()
     */
    public function testCleanHTML()
    {
        $allow 	 = '<span><a><br>';
        $str 	 = '<p style="text-align:center">Paragraph</p><strong style="color:red">Bold</strong><br><span style="color:red">Red</span><a href="#">Header</a>';
        $retorno = 'ParagraphBold<br><span style="color:red">Red</span><a href="#">Header</a>';
        $this->assertEquals($retorno, RW_Base::CleanHTML($str,$allow));
    }

    /**
     * Tests RW_Base::getCSV()
     */
    public function testGetCSV()
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
