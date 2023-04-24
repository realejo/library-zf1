<?php

declare(strict_types=1);

namespace RWTest\App\Model;

use PHPUnit\Framework\TestCase;
use RW_App_Model_Paginator;

class PaginatorTest extends TestCase
{
    private RW_App_Model_Paginator $paginator;


    public function getPaginator(): RW_App_Model_Paginator
    {
        if (!isset($this->paginator)) {
            $this->paginator = new RW_App_Model_Paginator();
        }
        return $this->paginator;
    }

    public function testGetPageRange()
    {
        // Recupera o Page Range
        $page = $this->getPaginator()->getPageRange();

        // Verifica se o conteudo veio correto
        $this->assertEquals(10, $page);
        $this->assertTrue(is_int($page));
        $this->assertFalse(is_string($page));
    }

    public function testGetCurrentPageNumber(): void
    {
        // Recupera o Current Page Number
        $page = $this->getPaginator()->getCurrentPageNumber();

        // Verifica se o conteudo veio correto
        $this->assertEquals(1, $page);
        $this->assertTrue(is_int($page));
        $this->assertFalse(is_string($page));
    }

    public function testGetItemCountPerPage(): void
    {
        // Recupera o Item Count Per Page
        $page = $this->getPaginator()->getItemCountPerPage();

        // Verifica se o conteudo veio correto
        $this->assertEquals(10, $page);
        $this->assertTrue(is_int($page));
        $this->assertFalse(is_string($page));
    }

    public function testSetPageRange(): void
    {
        // Recupera o Page Range
        $page = $this->getPaginator()->getPageRange();

        // Verifica se o conteudo veio correto
        $this->assertEquals(10, $page);
        $this->assertTrue(is_int($page));
        $this->assertFalse(is_string($page));

        // Define o valor do Page Range
        $page = $this->getPaginator()->setPageRange(15);

        // Verifica se o conteudo veio correto
        $this->assertTrue(is_object($page));

        // Recupera o Page Range
        $page = $this->getPaginator()->getPageRange();

        // Verifica se o conteudo veio correto
        $this->assertEquals(15, $page);
        $this->assertTrue(is_int($page));
        $this->assertFalse(is_string($page));
    }

    public function testSetCurrentPageNumber(): void
    {
        // Recupera o Current Page Number
        $page = $this->getPaginator()->getCurrentPageNumber();

        // Verifica se o conteudo veio correto
        $this->assertEquals(1, $page);
        $this->assertTrue(is_int($page));
        $this->assertFalse(is_string($page));

        // Define o Current Page Number
        $page = $this->getPaginator()->setCurrentPageNumber(2);

        // Verifica se o conteudo veio correto
        $this->assertTrue(is_object($page));

        // Recupera o Current Page Number
        $page = $this->getPaginator()->getCurrentPageNumber();

        // Verifica se o conteudo veio correto
        $this->assertEquals(2, $page);
        $this->assertTrue(is_int($page));
        $this->assertFalse(is_string($page));
    }

    public function testSetItemCountPerPage(): void
    {
        // Recupera o Item Count Per Page
        $page = $this->getPaginator()->getItemCountPerPage();

        // Verifica se o conteudo veio correto
        $this->assertEquals(10, $page);
        $this->assertTrue(is_int($page));
        $this->assertFalse(is_string($page));

        // Recupera o Item Count Per Page
        $page = $this->getPaginator()->setItemCountPerPage(20);

        // Verifica se o conteudo veio correto
        $this->assertTrue(is_object($page));

        // Recupera o Item Count Per Page
        $page = $this->getPaginator()->getItemCountPerPage();

        // Verifica se o conteudo veio correto
        $this->assertEquals(20, $page);
        $this->assertTrue(is_int($page));
        $this->assertFalse(is_string($page));
    }
}
