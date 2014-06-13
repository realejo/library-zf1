<?php
class BaseTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $adapter = null;

    /**
     * Prepares the environment before running ALL tests.
     */
    static public function setUpBeforeClass()
    {
        // Inicializa o ZF
        $bootstrap = new Zend_Application(
            'testing',
            APPLICATION_PATH . '/configs/application.ini'
        );
        $bootstrap->bootstrap();
    }

    function testBoostrap()
    {
        $this->assertTrue(true, 'Bootstrap OK');
    }

    /**
     * @return Zend_Db_Adapter_Abstract
     */
    public function getAdapter()
    {
        if ($this->adapter === null) {
            $this->adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
        }
        return $this->adapter;
    }

}
