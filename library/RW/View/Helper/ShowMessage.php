<?php
/**
 * ShowMessage helper
 *
 *  Mostra as mensagens para o usuário
 *
 * @category   RW
 * @package    RW_View
 * @subpackage Helper
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 *
 * @uses       Zend_View_Helper
 */
class RW_View_Helper_ShowMessage extends Zend_View_Helper_Abstract
{
    protected $_userMessageSession;

    public function __construct()
    {
        $this->_userSession = new Zend_Session_Namespace('cms');
    }

    /**
     * Monta box de mensagens do sistema
     *
     * @param mixed $message (OPCIONAL) Lista de mensagens independete da sessão do usuário
     *
     * @return string
     */
    public function showMessage($messages = null)  {

        // Verifica se foi enviado alguma mensagem
        if ( is_null($messages) ) {
            // Recupera as mensagens gravadas na sessão
            $messages = $this->_userSession->messages;

            // Limpa a sessão do usuário
            $this->_userSession->messages = array();

        // Verifica se é uma lista ou apenas uma mensagem
        } elseif (is_array($messages)) {
            if (isset($messages['type'])) {
                $messages = array($messages);
            }

        // Se for um formato inválido, não mostra nenhuma mensagem
        } else {
            $messages = array();
        }

        $return = '';
        if ( count($messages) > 0 ) {
            $return = PHP_EOL . '<!-- Mensagens -->' . PHP_EOL;
            foreach ( $messages as $msg) {
                $return .= "<div class=\"alert alert-{$msg['type']}\">" . PHP_EOL;
                $return .= $msg['message'] . PHP_EOL;
                $return .= '</div>' . PHP_EOL;
            }
            $return .= '<!-- FIM Mensagens -->' . PHP_EOL;
        }

        // Retorna as mensagens
        return $return;
    }
}