<?php

declare(strict_types=1);

namespace RWTest;

use PHPUnit\Framework\TestCase;
use RW_CNPJ;

class CNPJTest extends TestCase
{
    public function testValid()
    {
        $this->assertTrue(RW_CNPJ::valid('00577646000136'));
        $this->assertTrue(RW_CNPJ::valid('00.577.646/0001-36'));
        $this->assertTrue(!RW_CNPJ::valid('10.577.646/0001-36'));
        $this->assertTrue(RW_CNPJ::valid('0.577.646/0001-36'));
    }

    public function testFormat()
    {
        $this->assertTrue(RW_CNPJ::format('00577646000136') === '00.577.646/0001-36');
    }
}

