<?php
class RW_Debug extends Zend_Debug
{
	public $teste = array();
	
    static function log($message, $priority = Zend_Log::DEBUG)
    {
        if (APPLICATION_ENV != 'production') {
            $writer = new Zend_Log_Writer_Firebug();
            $logger = new Zend_Log($writer);
            $logger->log($message, $priority);
        }
    }

    static function logToFile($message, $file = null)
    {
        if (is_null($file)) {
            $hoje = new Zend_Date();
            $file = 'debug_log' . $hoje->toString('yyyyMMdd') . '.log';
        }
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/logs/' . $file);
        $logger = new Zend_Log($writer);
        $logger->log($message, Zend_Log::DEBUG);
    }


    /**
     * Envia uma mensagem de erro para a Realejo
     * @todo colocar no config/application.ini uma váriável dizendo se é pra enviar mesmo
     * @param string $message
     */
    static function sendError($message, $type='500', $to = 'sistemas@realejo.com.br')
    {
        switch ($type) {
            case '404': $subject = 'Página não encontrada'; break;
            case '500': $subject = 'Erro encontrado no site'; break;
            default   : $subject = $type;
        }

        if ('production' == APPLICATION_ENV || $type === $subject) {
            $oMailer = new RW_Mail(true);
            $oMailer->SendEmail(
                null, null, # usa nome e email padrões do applicaiotn.ini
                'Realejo', $to,
                "[rw_debug] $subject",
                $message, 'html'
            );
        }
    }
}
