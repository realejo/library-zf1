<?php

namespace RWTest\App\Loader;

use PHPUnit\Framework\TestCase;
use RW_App_Loader;

class AwarenessTest extends TestCase
{

    /**
     * Tests RW_App_Loader_Awareness->getLoader()
     */
    public function testGetLoader()
    {
        $mock = new ConcreteAppLoader();
        $this->assertInstanceOf('RW_App_Loader', $mock->getLoader());
    }

    /**
     * Tests RW_App_Loader_Awareness->setLoader()
     */
    public function testSetLoader()
    {
        $mock = new ConcreteAppLoader();
        $this->assertInstanceOf(RW_App_Loader::class, $mock->setLoader(new RW_App_Loader()));
    }
}

