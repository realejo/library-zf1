<?php
/**
 * RW_App_Model_Upload test case.
 */
class UploadTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var string
     */
    protected $dataPath = '/../../../assets/data';

    /**
     *
     * @var RW_App_Model_Upload
     */
    private $RW_App_Model_Upload;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // Verifica se a pasta do cache existe
        if (file_exists($this->dataPath)) {
            $this->rrmdir($this->dataPath);
        }
        $oldumask = umask(0);
        mkdir($this->dataPath, 0777, true);
        umask($oldumask);

        // TODO Auto-generated RW_App_Model_UploadTest::setUp()
        $this->RW_App_Model_Upload = new RW_App_Model_Upload(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated RW_App_Model_UploadTest::tearDown()
        $this->RW_App_Model_Upload = null;
        parent::tearDown();
    }

    /**
     * setAPPLICATION_DATA define o APPLICATION_DATA se não existir
     *
     * @return string
     */
    public function setAPPLICATION_DATA()
    {
        // Verifica se a pasta de cache existe
        if (defined('APPLICATION_DATA') === false) {
            define('APPLICATION_DATA', $this->dataPath);
        }
    }

    /**
     * Tests RW_App_Model_Upload::getUploadPath()
     */
    public function testGetUploadPath()
    {
        // TODO Auto-generated RW_App_Model_UploadTest::testGetUploadPath()
        $this->markTestIncomplete("getUploadPath test not implemented");
        RW_App_Model_Upload::getUploadPath(/* parameters */);
    }

    /**
     * Tests RW_App_Model_Upload::getUrlPath()
     */
    public function testGetUrlPath ()
    {
        // TODO Auto-generated RW_App_Model_UploadTest::testGetUrlPath()
        $this->markTestIncomplete("getUrlPath test not implemented");
        RW_App_Model_Upload::getUrlPath(/* parameters */);
    }

    /**
     * Tests RW_App_Model_Upload::getUploadRoot()
     */
    public function testGetUploadRoot ()
    {
        // TODO Auto-generated RW_App_Model_UploadTest::testGetUploadRoot()
        $this->markTestIncomplete("getUploadRoot test not implemented");
        RW_App_Model_Upload::getUploadRoot(/* parameters */);
    }

    /**
     * Tests RW_App_Model_Upload::getUrlRoot()
     */
    public function testGetUrlRoot ()
    {
        // TODO Auto-generated RW_App_Model_UploadTest::testGetUrlRoot()
        $this->markTestIncomplete("getUrlRoot test not implemented");
        RW_App_Model_Upload::getUrlRoot(/* parameters */);
    }
}

