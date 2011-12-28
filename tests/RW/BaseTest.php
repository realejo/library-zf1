<?php
require_once 'RW/Base.php';
/**
 * RW_Base test case.
 */
class BaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_Base
     */
    private $RW_Base;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated RW_BaseTest::setUp()
        $this->RW_Base = new RW_Base(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated RW_BaseTest::tearDown()
        $this->RW_Base = null;
        parent::tearDown();
    }
    /**
     * Constructs the test case.
     */
    public function __construct ()
    {
        // TODO Auto-generated constructor
    }
    /**
     * Tests RW_Base::RemoveAcentos()
     */
    public function testRemoveAcentos ()
    {

        $string  = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝŸàáâãäåæçèéêëìíîïñòóôõöùúûüýÿ';
        $retorno = 'AAAAAAAECEEEEIIIINOOOOOUUUUYYaaaaaaaeceeeeiiiinooooouuuuyy';
        $this->assertEquals($retorno,RW_Base::RemoveAcentos($string));
    }
    /**
     * Tests RW_Base::strip_tags_attributes()
     */
    public function testStrip_tags_attributes ()
    {
        // TODO Auto-generated RW_BaseTest::testStrip_tags_attributes()
        $this->markTestIncomplete(
        "strip_tags_attributes test not implemented");
        RW_Base::strip_tags_attributes(/* parameters */);
    }
    /**
     * Tests RW_Base::CleanFileName()
     */
    public function testCleanFileName ()
    {
        // TODO Auto-generated RW_BaseTest::testCleanFileName()
        $this->markTestIncomplete("CleanFileName test not implemented");
        RW_Base::CleanFileName(/* parameters */);
    }
    /**
     * Tests RW_Base::cleanInput()
     */
    public function testCleanInput ()
    {
        // TODO Auto-generated RW_BaseTest::testCleanInput()
        $this->markTestIncomplete("cleanInput test not implemented");
        RW_Base::cleanInput(/* parameters */);
    }
    /**
     * Tests RW_Base::seourl()
     */
    public function testSeourl ()
    {
        // TODO Auto-generated RW_BaseTest::testSeourl()
        $this->markTestIncomplete("seourl test not implemented");
        RW_Base::seourl(/* parameters */);
    }
    /**
     * Tests RW_Base::getSEOID()
     */
    public function testGetSEOID ()
    {
        // TODO Auto-generated RW_BaseTest::testGetSEOID()
        $this->markTestIncomplete("getSEOID test not implemented");
        RW_Base::getSEOID(/* parameters */);
    }
    /**
     * Tests RW_Base::CleanHTML()
     */
    public function testCleanHTML ()
    {
        // TODO Auto-generated RW_BaseTest::testCleanHTML()
        $this->markTestIncomplete("CleanHTML test not implemented");
        RW_Base::CleanHTML(/* parameters */);
    }
    /**
     * Tests RW_Base::getCSV()
     */
    public function testGetCSV ()
    {
        // TODO Auto-generated RW_BaseTest::testGetCSV()
        $this->markTestIncomplete("getCSV test not implemented");
        RW_Base::getCSV(/* parameters */);
    }
}

