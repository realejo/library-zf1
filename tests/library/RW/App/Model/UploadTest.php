<?php

namespace RWTest\App\Model;

use RW_App_Model_Upload;
use RWTest\TestAssets\BaseTestCase;

/**
 * RW_App_Model_Upload test case.
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class UploadTest extends BaseTestCase
{
    /**
     * @var RW_App_Model_Upload
     */
    private $RW_App_Model_Upload;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // Verifica se a pasta do APPLICATION DATA
        $this->setApplicationConstants()->clearApplicationData();

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

        $this->clearApplicationData();

        parent::tearDown();
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
    public function testGetUrlPath()
    {
        // TODO Auto-generated RW_App_Model_UploadTest::testGetUrlPath()
        $this->markTestIncomplete("getUrlPath test not implemented");
        RW_App_Model_Upload::getUrlPath(/* parameters */);
    }

    /**
     * Tests RW_App_Model_Upload::getUploadRoot()
     */
    public function testGetUploadRoot()
    {
        // TODO Auto-generated RW_App_Model_UploadTest::testGetUploadRoot()
        $this->markTestIncomplete("getUploadRoot test not implemented");
        RW_App_Model_Upload::getUploadRoot(/* parameters */);
    }

    /**
     * Tests RW_App_Model_Upload::getUrlRoot()
     */
    public function testGetUrlRoot()
    {
        // TODO Auto-generated RW_App_Model_UploadTest::testGetUrlRoot()
        $this->markTestIncomplete("getUrlRoot test not implemented");
        RW_App_Model_Upload::getUrlRoot(/* parameters */);
    }
}

