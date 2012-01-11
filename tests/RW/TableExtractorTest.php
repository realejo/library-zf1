<?php
require_once 'RW/TableExtractor.php';
/**
 * RW_tableExtractor test case.
 */
class TableExtractorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_tableExtractor
     */
    private $RW_TableExtractor;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated TableExtractorTest::setUp()
        $this->RW_TableExtractor = new RW_TableExtractor(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated TableExtractorTest::tearDown()
        $this->RW_TableExtractor = null;
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
     * Tests RW_tableExtractor->__construct()
     */
    public function test__construct ()
    {
        // TODO Auto-generated TableExtractorTest->test__construct()
        $this->markTestIncomplete("__construct test not implemented");
        $this->RW_TableExtractor->__construct(/* parameters */);
    }
    /**
     * Tests RW_tableExtractor->loadHTML()
     */
    public function testLoadHTML ()
    {
        // TODO Auto-generated TableExtractorTest->testLoadHTML()
        $this->markTestIncomplete("loadHTML test not implemented");
        $this->RW_TableExtractor->loadHTML(/* parameters */);
    }
    /**
     * Tests RW_tableExtractor->extractTable()
     */
    public function testExtractTable ()
    {
        // TODO Auto-generated TableExtractorTest->testExtractTable()
        $this->markTestIncomplete("extractTable test not implemented");
        $this->RW_TableExtractor->extractTable(/* parameters */);
    }
    /**
     * Tests RW_tableExtractor->cleanHTML()
     */
    public function testCleanHTML ()
    {
        // TODO Auto-generated TableExtractorTest->testCleanHTML()
        $this->markTestIncomplete("cleanHTML test not implemented");
        $this->RW_TableExtractor->cleanHTML(/* parameters */);
    }
    /**
     * Tests RW_tableExtractor->prepareArray()
     */
    public function testPrepareArray ()
    {
        // TODO Auto-generated TableExtractorTest->testPrepareArray()
        $this->markTestIncomplete("prepareArray test not implemented");
        $this->RW_TableExtractor->prepareArray(/* parameters */);
    }
    /**
     * Tests RW_tableExtractor->createArray()
     */
    public function testCreateArray ()
    {

        // TODO Auto-generated TableExtractorTest->testCreateArray()
        $this->markTestIncomplete("createArray test not implemented");
        $this->RW_TableExtractor->createArray(/* parameters */);
    }
}

