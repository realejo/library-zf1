<?php

namespace RWTest;

use PHPUnit\Framework\TestCase;
use RW_Image;

class ImageTest extends TestCase
{
    /**
     * @var RW_Image
     */
    private $Image;

    /**
     * @var string
     */
    private $imgPath;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->Image = new RW_Image(/* parameters */);

        // path para as imagens
        $this->imgPath = realpath(TEST_ROOT . '/assets/_files/');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        // TODO Auto-generated ImageTest::tearDown()
        $this->Image = null;
        parent::tearDown();
    }

    /**
     * Tests RW_Image->open()
     */
    public function testOpen()
    {
        //Abrir JPG
        $file = $this->imgPath . '/exemplo.jpg';
        self::assertTrue($this->Image->open($file));
        // Verifica se foi criado o resource
        self::assertTrue($this->Image->isLoaded());

        // Verifica se o mimetype é JPEG
        $mimeType = $this->Image->mimeType;
        self::assertSame('jpeg', $mimeType);

        // Fecha a imagem
        $this->Image->close();

        //Abrir arquivo inexistente
        $file = $this->imgPath . '/naoexiste.jpg';
        self::assertFalse($this->Image->open($file));

        //Abrir PNG
        $file = $this->imgPath . '/exemplo.png';
        self::assertTrue($this->Image->open($file));

        //Verifica se foi criado o resource
        self::assertTrue($this->Image->isLoaded());

        // Verifica se o mimetype é PNG
        $mimeType = $this->Image->mimeType;
        //$this->assertEqual('png',$mineType);
        self::assertSame('png', $mimeType);

        // Fecha a imagem
        $this->Image->close();

        //Abrir GIF
        $file = $this->imgPath . '/exemplo.gif';
        self::assertTrue($this->Image->open($file));

        //Verifica se foi criado o resource
        self::assertTrue($this->Image->isLoaded());

        // Verifica se o mimetype é gif
        $mimeType = $this->Image->mimeType;
        //$this->assertEqual('gif',$mineType);
        self::assertSame('gif', $mimeType);

        // Fecha a imagem
        $this->Image->close();

        //Abrir um arquivo em formato nao suportado
        $file = $this->imgPath . '/exemplo.tif';
        self::assertFalse($this->Image->open($file));
    }

    /**
     * Tests RW_Image->close()
     */
    public function testClose()
    {
        $file = $this->imgPath . '/exemplo.jpg';

        $this->Image->open($file);

        //Fecha o arquivo
        self::assertTrue($this->Image->close());
    }

    /**
     * Tests RW_Image->isLoaded()
     */
    public function testIsLoaded()
    {
        // Cria o arquivo temporário
        copy($this->imgPath . '/exemplo.jpg', $this->imgPath . '/temp/temp.jpg');

        // Deifne o arquivo a ser usado para os testes
        $file = $this->imgPath . '/temp/temp.jpg';
        $this->Image->open($file);

        //Verifica o resource
        self::assertTrue($this->Image->isLoaded());

        // Fecha a imagem
        $this->Image->close();

        // Verifica a imagem
        //$this->assertNull($this->Image->isLoaded());

        // Abre a imagem novamente
        $file = $this->imgPath . '/temp/temp.jpg';
        $this->Image->open($file);

        // Salva a imagem sem a opção de fechar
        $this->Image->save();

        // Verifica se is loades
        self::assertTrue($this->Image->isLoaded());

        // Salva a imagem com opção de fechar
        $this->Image->save($file, true);

        // veriicca o isLoaded
        self::assertFalse($this->Image->isLoaded());

        // apago o arquivo temporário
        unlink($file);
    }

    /**
     * Tests RW_Image->save()
     */
    public function testSave()
    {
        // Cria o arquivo temporário
        copy($this->imgPath . '/exemplo.jpg', $this->imgPath . '/temp/temp.jpg');

        $file = $this->imgPath . '/temp/temp.jpg';

        $this->Image->open($file);

        self::assertTrue($this->Image->save(true));
    }

    /**
     * Image->resize() sem imagem carregada
     */
    public function testResizeSemImagemCarregada()
    {
        $this->expectException(\Exception::class);
        $this->Image->resize('500', '150', true, true);
    }

    /**
     * Tests RW_Image->resize()
     */
    public function testResize()
    {
        // Cria o arquivo temporário
        copy($this->imgPath . '/exemplo.jpg', $this->imgPath . '/temp/temp.jpg');

        //Abrir JPG
        $file = $this->imgPath . '/temp/temp.jpg';
        $this->Image->open($file);

        //Reduz o tamanho do JPG com crop
        self::assertTrue($this->Image->resize(500, 150, true, true));

        //Salva Mudanças
        self::assertTrue($this->Image->save());

        //Pega tamanho da imagens após mudança
        list($width, $height, $type, $attr) = getimagesize($file);

        //Compra os tamanhos passados e reais da imagem
        self::assertSame(500, $width);
        self::assertSame(150, $height);

        //Fecha o arquivo
        $this->Image->close();

        //Abrir JPG
        $this->Image->open($file);

        //Aumenta a imagem JPG com Crop e forçado
        self::assertTrue($this->Image->resize(1000, 2, true, true));
        self::assertTrue($this->Image->save());

        //Pega tamanho da imagem após mudança
        list($width, $height, $type, $attr) = getimagesize($file);

        //Compara os tamanhos pedidos e reais
        self::assertSame(1000, $width);
        self::assertSame(2, $height);

        //Fecha a imagem
        $this->Image->close();

        //Abrir JPG
        $this->Image->open($file);

        //Reduz a imagem JPG com Crop e forçado
        self::assertTrue($this->Image->resize(50, 1, true, true));
        self::assertTrue($this->Image->save());

        //Retornar os atributos da imagem
        list($width, $height, $type, $attr) = getimagesize($file);

        self::assertSame(50, $width);
        self::assertSame(1, $height);
        unlink($file);

        //Sem Crop e reduzindo forçado
        copy($this->imgPath . '/exemplo_600x800.jpg', $this->imgPath . '/temp/temp.jpg');

        //Abrir JPG
        $this->Image->open($file);

        //Reduzir imagem sem crop e forçado
        self::assertTrue($this->Image->resize(113, 150, false, true));
        self::assertTrue($this->Image->save());

        //Pega tamanho da imagem após mudança
        list($width, $height, $type, $attr) = getimagesize($file);

        //Compra os tamanhos pedidos e reais
        self::assertSame(113, $width);
        self::assertSame(150, $height);

        //Fecha a imagem
        $this->Image->close();

        //Abrir JPG
        $this->Image->open($file);

        //Reduz a imagem JPG com Crop e forçado
        self::assertTrue($this->Image->resize('10', '149', false, true));
        self::assertTrue($this->Image->save());

        //Retornar os atributos da imagem
        list($width, $height, $type, $attr) = getimagesize($file);

        self::assertSame(10, $width);
        self::assertSame(13, $height);

        unlink($file);

        /*COM ARQUIVO PNG*/

        // Cria o arquivo temporário
        copy($this->imgPath . '/exemplo.png', $this->imgPath . '/temp/temp.png');

        //Abrindo arquivo
        $file = $this->imgPath . '/temp/temp.png';
        $this->Image->open($file);

        //aumentando o PNG COM CROP
        self::assertTrue($this->Image->resize(1000, 500, true, true));
        self::assertTrue($this->Image->save());

        //conferindo os valores salvos
        list($width, $height, $type, $attr) = getimagesize($file);
        self::assertSame(1000, $width);
        self::assertSame(500, $height);

        //Abirndo o PNG
        $this->Image->open($file);

        //Reduzir PNG
        self::assertTrue($this->Image->resize(200, 100));
        self::assertTrue($this->Image->save());

        //conferindo os valores
        list($width, $height, $type, $attr) = getimagesize($file);
        self::assertSame(200, $width);
        self::assertSame(100, $height);

        //fechando o arquivo
        $this->Image->close();

        //deletando o arquivo
        unlink($file);

        // Cria o arquivo temporário
        copy($this->imgPath . '/exemplo.jpg', $this->imgPath . '/temp/temp.jpg');
        $file = $this->imgPath . '/temp/temp.jpg';

        //abrindo o arquivo
        $this->Image->open($file);

        //rezudindo o arquivo SEM CROP
        self::assertTrue($this->Image->resize(1000, 10, false, true));
        self::assertTrue($this->Image->save());

        //fechando o arquivo
        $this->Image->close();

        //deletando o arquivo
        unlink($file);
    }

    /**
     * Tests RW_Image->removeMetadata()
     */
    public function testRemoveMetadata()
    {
        // Cria o arquivo temporário
        copy($this->imgPath . '/exemplo_600x800.jpg', $this->imgPath . '/temp/temp.jpg');
        $file = $this->imgPath . '/temp/temp.jpg';

        //Salva os metadados antes de alterar
        $metadados = exif_read_data($file);

        //Abre o arquivo
        $this->Image->open($file);

        //remove os metadados
        self::assertTrue($this->Image->removeMetadata());

        //salva as alterações
        $this->Image->save(null, true);

        //Metadado atuais
        $atual = exif_read_data($file);

        //Compara os metadados
        self::assertNotEquals($metadados, $atual);
        unlink($file);
    }
}

