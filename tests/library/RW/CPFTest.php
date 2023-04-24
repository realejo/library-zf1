<?php

declare(strict_types=1);

namespace RWTest;

use PHPUnit\Framework\TestCase;
use RW_CPF;

class CPFTest extends TestCase
{
    public function testValid(): void
    {
        self::assertTrue(RW_CPF::valid('06003014601'));
        self::assertFalse(RW_CPF::valid('111.111.111-01'));
    }

    public function testFormat(): void
    {
        self::assertEquals('060.030.146-01', RW_CPF::format('06003014601'));
    }
}

