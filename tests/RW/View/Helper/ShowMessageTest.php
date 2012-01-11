<?php
/**
 * RW_View_Helper_ShowMessage test case.
 *
 * @category   RW
 * @package    RW_View_Helper
 * @subpackage UnitTests
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */

require_once 'RW/View/Helper/ShowMessage.php';
require_once 'RW/Action/Helper/AddUserMessage.php';

class ShowMessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RW_View_Helper_ShowMessage
     */
    private $RW_View_Helper_ShowMessage;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
    	Zend_Session::$_unitTestEnabled = true;
        parent::setUp();
        // TODO Auto-generated ShowMessageTest::setUp()
        $this->_userSession 					 = new Zend_Session_Namespace('cms');
        $this->RW_View_Helper_ShowMessage 		 = new RW_View_Helper_ShowMessage();
        $this->RW_Action_Helper_AddUserMessage   = new RW_Controller_Action_Helper_AddUserMessage();


    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated ShowMessageTest::tearDown()
        $this->RW_View_Helper_ShowMessage = null;
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
     * Tests RW_View_Helper_ShowMessage->__construct()
     */
    public function test__construct ()
    {
        // TODO Auto-generated ShowMessageTest->test__construct()
        $this->RW_View_Helper_ShowMessage->__construct();
        Zend_Session::namespaceIsset('cms');

    }
    /**
     * Tests RW_View_Helper_ShowMessage->showMessage()
     */
    public function testShowMessage ()
    {

    	$type1	= 'notice';
    	$message1 = 'apenas um teste';

    	$return1 = '';
        $return1 = PHP_EOL . '<!-- Mensagens -->' . PHP_EOL;
        $return1 .= "<div class=\"alert-message $type1\">" . PHP_EOL;
        $return1 .= $message1 . PHP_EOL;
        $return1 .= '</div>' . PHP_EOL;
        $return1 .= '<!-- FIM Mensagens -->' . PHP_EOL;


        $type2	= 'warning';
    	$message2 = 'outra mensagem';

    	$return2 = '';
        $return2 = PHP_EOL . '<!-- Mensagens -->' . PHP_EOL;

        $return2 .= "<div class=\"alert-message $type1\">" . PHP_EOL;
        $return2 .= $message1 . PHP_EOL;
        $return2 .= '</div>' . PHP_EOL;

        $return2 .= "<div class=\"alert-message $type2\">" . PHP_EOL;
        $return2 .= $message2 . PHP_EOL;
        $return2 .= '</div>' . PHP_EOL;

        $return2 .= '<!-- FIM Mensagens -->' . PHP_EOL;


        // grava uma mensagem no session
    	$this->RW_Action_Helper_AddUserMessage->direct($type1, $message1);


        // retorna uma mensagem do session
        $this->assertEquals($return1, $this->RW_View_Helper_ShowMessage->showMessage());

        // Grava duas mensagens no session
    	$this->RW_Action_Helper_AddUserMessage->direct($type1, $message1);
    	$this->RW_Action_Helper_AddUserMessage->direct($type2, $message2);

        // retorna duas mensagem do session
        $this->assertEquals($return2, $this->RW_View_Helper_ShowMessage->showMessage());


        // retorna nenhuma mensagem pois o session está vazio
        $this->assertEquals('' , $this->RW_View_Helper_ShowMessage->showMessage());

        // retorna nenhuma mensagem pois o valor é inválido
        $this->assertEquals('' , $this->RW_View_Helper_ShowMessage->showMessage('teste pra dar erro'));

        // retorna mensagem forçada
        $this->assertEquals($return1, $this->RW_View_Helper_ShowMessage->showMessage(array('type'=>$type1,'message'=>$message1)));
        $this->assertEquals($return2, $this->RW_View_Helper_ShowMessage->showMessage(array(array('type'=>$type1,'message'=>$message1),array('type'=>$type2,'message'=>$message2))));
    }
}

