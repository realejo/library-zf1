<?php
/**
 * RW_Search test case.
 *
 * @link      http://github.com/realejo/library-zf1
 * @copyright Copyright (c) 2011-2014 Realejo Design Ltda. (http://www.realejo.com.br)
 */

class SearchTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var RW_Search
     */
    private $RW_Search;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated SearchTest::setUp()
        $this->RW_Search = new RW_Search(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated SearchTest::tearDown()
        $this->RW_Search = null;
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
     * Tests RW_Search::getIndex()
     */
    public function testGetIndex ()
    {
        // TODO Auto-generated SearchTest::testGetIndex()
        $this->markTestIncomplete("getIndex test not implemented");
        RW_Search::getIndex(/* parameters */);
    }
    /**
     * Tests RW_Search::resumoHighlight()
     */
    public function testResumoHighlight ()
    {
        // TODO Auto-generated SearchTest::testResumoHighlight()
        $this->markTestIncomplete(
        "resumoHighlight test not implemented");
        RW_Search::resumoHighlight(/* parameters */);
    }
    /**
     * Tests RW_Search::fixHighlight()
     */
    public function testFixHighlight ()
    {
        // TODO Auto-generated SearchTest::testFixHighlight()
        $this->markTestIncomplete("fixHighlight test not implemented");
        RW_Search::fixHighlight(/* parameters */);
    }
    /**
     * Tests RW_Search::simples()
     */
    public function testSimples ()
    {
        // TODO Auto-generated SearchTest::testSimples()
        $this->markTestIncomplete("simples test not implemented");
        RW_Search::simples(/* parameters */);
    }
}

