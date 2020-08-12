<?php

namespace RWTest;

use Exception;
use RW_Search;
use RWTest\TestAssets\BaseTestCase;

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
     * getCachePath sem nome da pasta
     */
    public function testGetIndexRootSemAPPLICATION_DATA()
    {
        $this->expectException(Exception::class);
        if (defined("APPLICATION_DATA")) {
            throw new \RuntimeException('APPLICATION_DATA já foi definido em outro lugar');
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
        self::assertNotNull($path, 'a path foi retornado');
        self::assertDirectoryExists($path, "$path é um diretório");
        self::assertTrue(is_writable($path), 'tem permissão de escrita');

        $this->clearApplicationData();
    }

}

