<?php
/**
 * AddUserMessage helper
 *
 * Grava as mensagens ao usuário
 *
 * @category   RW
 * @package    RW_Controller
 * @subpackage RW_Controller_Action_Helper
 * @author     Realejo $Author$
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.relaejo.com.br)
 *
 * @uses 	   Zend_Controller_Action_Helper
 */
class RW_Controller_Action_Helper_AddUserMessage extends Zend_Controller_Action_Helper_Abstract
{

	/**
	 * @var Zend_Session_Namespace
	 */
    protected $_userMessageSession;

	public function __construct()
	{
		$this->_userSession = new Zend_Session_Namespace('cms');
	}

    /**
     * Adiciona as mensagens a sessao
     *
	 * @param string $type    	Tipo de Mensagem
	 * @param string $message	Mensagem a ser gravada
     *
	 */
	public function direct($type, $messagem) {
	    // Verifica se há todos os itens
	    if (empty($type)) throw new Exception('Tipo de mensagem não definido');
	    if (empty($messagem)) throw new Exception('Mensagem não definida');

	    // Verifica se a lista de mensagens já existe
        if ( !isset($this->_userSession->messages) )
            $this->_userSession->messages = array();

        // Grava a mensagem ao usuário na lista
        $this->_userSession->messages[] = array('type'=>$type, 'message'=>$messagem);
	}
}