<?php
/**
 * Bilioteca para envio de email
 * @author Rodrigo Capilé
 * @copyright Realejo 2010
 *
 * @uses Zend_Mail
 *
 * @todo impedir que o email va para o cliente sem querer. Deixar tudo no "drop folder"
 * @todo header injection
 * foreach($_REQUEST as $fields => $value)
 *      if(eregi("TO:", $value) || eregi("CC:", $value) || eregi("CCO:", $value) || eregi("Content-Type", $value))
 *          exit("ERROR: Code injection attempt denied! Please don't use the following sequences in your message: 'TO:', 'CC:', 'CCO:' or 'Content-Type'.");
 */
class RW_Mail
{

    /**
     * Nome padrão para os emails enviados pelo site.
     * @var strine
     */
    private $_name;

    /**
     * Email padrão utilizado no site
     * @var string
     */
    private $_email;

    /*
     * Return Path de configuração. precisa ser um do mesmo domínio
     * @var string
     */
    private $_returnPath;

    /**
     * Método para ser usado no envio. Válido somente quando ENV == 'production'
     * @var string
     */
    private $_type;

    /**
     * Configurações de SMTP
     * @var string
     */
    private $_username;
    /**
     *  @var string
     */
    private $_password;

    public function __construct($isException = false)
    {
        // Carrega as configurações do config
		$configpath = APPLICATION_PATH . "/../configs/application.ini";
        if ( !file_exists($configpath) ) {
            // procura dentro do application
            $configpath = APPLICATION_PATH . "/configs/application.ini";
            if ( !file_exists($configpath) ) {
                throw new Exception("Arquivo de configuração application.ini não encontrado do diretório /configs");
            }
        }
        $config = new Zend_Config_Ini($configpath, APPLICATION_ENV);

        $this->_name       = $config->cms->email->name;
        $this->_email      = $config->cms->email->email;
        $this->_returnPath = $config->cms->email->returnPath;
        $this->_type       = ($isException)? 'exception' : $config->cms->email->type;
        $this->_username   = $config->cms->email->smtp->username;
        $this->_password   = $config->cms->email->smtp->password;

        // Configura o método de envio
        if ($this->_type == 'exception') {
            $transport = new Zend_Mail_Transport_Sendmail('-f'. $this->_returnPath);

        // Envio do servidor local. Deve impedir que o cliente receba sem querer
        } elseif (APPLICATION_ENV != 'production') {
            $this->_name .= ' (teste local)';
            $this->_email = 'sistemas@realejo.com.br';
            $transport = new Zend_Mail_Transport_Sendmail("-fsistemas@realejo.com.br");

        // Configurações da Locaweb
        } elseif ( $this->_type == 'locaweb' ) {
            /*
             * Return-Path padrão da Locaweb
             * sendmail_path = /usr/sbin/sendmail -t -i -r'email@seudominio.com.br'
             */
            $transport = new Zend_Mail_Transport_Sendmail("-f{$this->_returnPath}");

        // Configurações do GMail
        } elseif ( $this->_type == 'gmail' ) {
            $serverconfig = array(
                'auth' => 'login',
                'username' => $this->_username,
                'password' => $this->_password,
                'ssl' => 'ssl', //'tls',
                'port' => 465
            );
            $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $serverconfig);

        // Configuração genérica de SMTP
        } elseif ( $this->_type == 'smtp' ) {
            $serverconfig = array(
                'auth' => 'login',
                'username' => $this->_username,
                'password' => $this->_password
            );

            // Verifica se há SSL
            if ( isset($config->cms->email->smtp->ssl) && $config->cms->email->smtp->ssl != '')
                $config['ssl'] = $config->cms->email->smtp->ssl;

            // veriufica se há uma porta definida
            if ( isset($config->cms->email->smtp->port) &&  $config->cms->email->smtp->port != '')
                $config['port'] = $config->cms->email->smtp->port;

            // Configura o transport
            $transport = new Zend_Mail_Transport_Smtp($config->cms->email->smtp->host, $serverconfig);

        } else {
            throw new Exception ( 'Tipo de envio <b>' . $this->_type . '</b> não definido em RW_Mail');
        }

        Zend_Mail::setDefaultTransport($transport);
    }

    public function SendEmail($replyName, $replyEmail, $toName, $toEmail, $subject, $message, $opt = array() )
    {
        // Verifica a codificação
        $replyName  = $this->_fixEncoding($replyName);
        $replyEmail = $this->_fixEncoding($replyEmail);
        $toName     = $this->_fixEncoding($toName);
        $toEmail    = $this->_fixEncoding($toEmail);
        $subject    = $this->_fixEncoding($subject);
        $message    = $this->_fixEncoding($message);

        // Verifica o email do destinatário
        if ( $toEmail == '' || is_null($toEmail) ) {
            throw new Exception ( 'Não há email de destino definido em RW_Mail');
        }

        // Verifica o nome do destinatário
        if ( $toName == '' || is_null($toName) ) {
            $toName = $toEmail;
        }

        // Verifica o nome do remetente
        if ( $replyName == '' || is_null($replyName) ) {
            $replyName = $this->_name;
        }

        // Verifica o email de resposta do remetente
        if ( $replyEmail == '' || is_null($replyEmail) ) {
            $replyEmail = $this->_email;
        }

        // Verifica se é debug
        if (false) {
            echo "
                <h3>Dados do Email</h3>
                De: $replyName &lt;{$this->email}&gt;<br>
                Para: $toName &lt;$toEmail&gt;<br>
                Reply-to: $replyName &lt;$replyEmail&gt;<br>
                BCC: <%=bcc%><br>
            <hr>
                $message
            <hr>
            ";
        }

        // Cria o Zend_Mail
        $oMailer = new Zend_Mail();

        // Nome e Email do Remetente
        $oMailer->setFrom($this->_returnPath, $replyName);

        // Nome e Email do Destinatario
        if (is_array($toEmail)) {
            foreach ($toEmail as $e=>$n) {
                if ($n != $e) {
                    $oMailer->addTo($e, $n);
                } else {
                    $oMailer->addTo($e);
                }
            }
        } else {
            if ($toName != $toEmail) {
                $oMailer->addTo($toEmail, $toName);
            } else {
                $oMailer->addTo($toEmail);
            }
        }

        // Resposta do email
        if ($replyEmail != $this->_returnPath) {
            $oMailer->setReplyTo($replyEmail);
        }

        // E-mail com cópia oculta
        if ( is_array($opt) && isset($opt['bcc']) ) {
            $bcc = $opt['bcc'];
            if ( is_string($bcc) ) {
				$oMailer->addBcc($bcc);
            } else {
                foreach ($bcc as $email) {
                    $oMailer->addBcc($email);
                }
            }
        }

        // Email com cópia
        if ( is_array($opt) && isset($opt['cc']) ) {
            $cc = $opt['cc'];
            if ( is_string($cc) ) {
                $oMailer->addCc($cc);
            } else {
                foreach ($cc as $name=>$email) {
                    $oMailer->addCc($email, $name);
                }
            }
        }

        // Assunto do E-mail
        $oMailer->setSubject($subject);

        // Cria a mensagem
        $msgText = null;
        $msgHtml = null;
        if (is_string($message) && !isset($opt['html']) ) {
            $msgText = $message;
        } elseif (is_string($message) && isset($opt['html']) ) {
            $msgHtml = $message;
            $oMailer->setBodyHtml($message);
        } elseif ( is_array($message) ) {
            if ( isset($message['text']) ) {
                $msgText = $message['text'];
            }
            if ( isset($message['html']) ) {
                $msgHtml = $message['html'];
            }
        }

        // Cria o TXT a partir do HTML
        if (is_null($msgText) && !is_null($msgHtml)) $msgText = $this->_extractText($msgHtml);

        if ( !is_null($msgText) ) $oMailer->setBodyText($msgText);
        if ( !is_null($msgHtml) ) $oMailer->setBodyHtml($msgHtml);
        if ( is_null($msgText) && is_null($msgHtml) ) die("Não foi possível definir o corpo da mensagem.");

        //Envia o email
        $oMailer->send();
    }

    /**
     * Enviar um feedback de acordo com as configurações do arquivo .INI
     *  - O arquivo deve estar na pasta /configs
     *  - O nome do arquivo deve ser feedback.ARQUIVO.ini
     *
     * @todo colocar valores padrões para limpar o INI e previnir possiveis erros
     * @todo verificar SPAM
	 * @todo colocar em uma classe separada
     *
     * @param array 	$post campos do formulário
     * @param string	$config arquivo ini com as configurações
     *
     * @uses   Zend_Config_Ini
     * @throws RW_Exception
     * @see    RW_Exception
     */
    public function sendFeedback($post, $configfile)
    {
        $configpath = realpath(APPLICATION_PATH . "/../configs/feedback.$configfile.ini");
        if ( !file_exists($configpath) ) {
            // Tenta outro diretório
            $configpath = realpath(APPLICATION_PATH . "/configs/feedback.$configfile.ini");
        }

        if ( !file_exists($configpath) ) {
            throw new Exception("Arquivo de configuração '$configfile' não encontrado do diretório /configs");
        }

        $config = new Zend_Config_Ini($configpath, APPLICATION_ENV);
        $config = $config->toArray();
        $opts = array();

        // arruma os destinatarios
        foreach ($config['destinatarios'] as $id=>$campo) {
            $c = explode(':',$campo);
            if ( count($c) == 1) {
                $config['destinatarios'][$c[0]] = null;
            } elseif (count($c) == 2) {
                $config['destinatarios'][$c[1]] = $c[0];
            }
            unset($config['destinatarios'][$id]);
        }

        // arruma os campos
        foreach ($config['campos'] as $id=>$campo) {
            $c = explode(':',$campo);
            if ( count($c) == 1) {
                $config['campos'][$c[0]] = $c[0];
            } elseif (count($c) == 2) {
                $config['campos'][$c[0]] = $c[1];
            }
            unset($config['campos'][$id]);
        }

        // Verifica se há variáveis no titulo
        if (strpos($config['titulo'], '$') !== false) {
            foreach ($post as $c=>$v) {
                $config['titulo'] = str_replace('$'.$c, $v, $config['titulo']);
            }
        }

        // Verifica se vai usar um modelo
        if ( isset($config['modelo'])) {
            $view = new Zend_View();
            $view->setScriptPath(APPLICATION_PATH . '/views/scripts');
            $html = $emailCliente = $view->partial('/_modelos/feedback/' . $config['modelo'] . '.phtml',
                array(
                    'valores' => $post,
                    'config' => $config
                ));
            $text = $this->_extractText($html);
        } else {
            // Cria o HTML para enviar no email
            $html = '<table>';
            $text = '';
            foreach ($config['campos'] as $campo=>$nome) {
                if ( (isset($post[$campo]) && ($post[$campo] != '') ) || $config['mostraVazios'] == 1) {
                    $text .= "$nome: " . ( (isset($post[$campo]))?$post[$campo]:'') . "\n";

                    $html .= '<tr>';
                        $html .= '<td align="right" valign="top"><font face="verdana" size="2">' . $nome. '</font></td>';
                        $html .= '<td></td>';
                        $html .= '<td valign="top"><font face="verdana" size="2">' . ( (isset($post[$campo]))?$post[$campo]:'' ) . '</font></td>';
                    $html .= '</tr>';
                }
            }
            $html .= "</table>";
        }

        // coloca a assinatura
        if (isset($text) && $text != '') {
            $text .= "\n\n";
            $text .= 'Realejo Feedback v1.0' . "\n";
            $text .= 'Este e-mail foi gerado automaticamente em '. date("d/m/y H:m:s") . "\n";
            $text .= '(C)2010, Realejo (http://www.realejo.com.br)' . "\n";
        }
        if (isset($html) && $html != '') {
            $html .= '<p>&nbsp;</p><center>';
            $html .= '<table width=95% cellspacing=0 cellpadding=0>';
            $html .= '<tr><td align="right" colspan="2"><font face="verdana" size="1">Este e-mail foi gerado automaticamente em '. date("d/m/y H:m:s") . '.</font></td></tr>';
            $html .= '<tr bgcolor="#6E6E6E"><td height="1" colspan="2"></td></tr>';
            $html .= '<tr>';
            $html .= '<td><font size="1" face="verdana">Realejo Feedback v1.0</font></td>';
            $html .= '<td align=right>';
            $html .= '<font size="1" face="verdana">&copy; 2010, <a href="http://www.realejo.com.br"><font color="black">Realejo</font></a></font>';
            $html .= '</td></tr>';
            $html .= '</table></center>';
        }


        if ($config['verificar'] == 1 && APPLICATION_ENV != 'production') {
            echo "<h1>Verificação do Feedback</h1>";
            echo "<p>veja abaixo as configurações e o email enviado</p>";
            echo "<h2>Configuração feedback.$configfile.ini</h2>";
            RW_Debug::dump($config,'$config');
            echo "<hr/>";
            RW_Debug::dump($post,'$post');
            echo "<hr/>";
            echo "<h2>HTML</h2>$html <hr>";
            echo "<h2>TEXTO</h2><pre>$text</pre><hr>";
        }

        if ($config['destinatarios_unir'] == 1) {
            // verifica se tem cópia oculta
            if (isset($config['bcc']) && $config['bcc'] != '') $opts['bcc'] = $config['bcc'];

            $this->SendEmail(
                $post[$config['nome']], $post[$config['email']],
                null, $config['destinatarios'],
                $config['titulo'],
                array('html'=>$html, 'text'=>$text ),
                $opts
            );
        } else {
            foreach ($config['destinatarios'] as $email=>$nome) {
                $this->SendEmail(
                    $post[$config['nome']], $post[$config['email']],
                    $nome, $email,
                    $config['titulo'],
                    array('html'=>$html, 'text'=>$text )
                );
            }

            // verifica se tem cópia oculta
            // @todo permitir que tenha nome no bcc
            // @todo informar que recebeu os emails
            if (isset($config['bcc']) && $config['bcc'] != '') {
                $this->SendEmail(
                    $post[$config['nome']], $post[$config['email']],
                    $config['bcc'], $config['bcc'],
                    $config['titulo'],
                    array('html'=>$html, 'text'=>$text )
                );
            }
        }
        if ($config['verificar'] == 1 && APPLICATION_ENV != 'production') {
            die('<hr/>email enviado');
        }

    }

    /**
     * Remove o encoding UTF-8 para não gerar caracteres inválidos no email
     *
     * @param array|str $str
     * @return array|str
     * @todo não detecta UTF-8 depois de utf8_decode
     */
    private function _fixEncoding($str) {
        if ( is_array($str) ) {
            foreach ($str as $key=>$value) {
                $key   = $this->_fixEncoding( $key );
                $value = $this->_fixEncoding($value);
                $str[$key] = $value;
            }
        } elseif ( $this->_check_utf8( $str ) ) {
             $str = utf8_decode( $str );
        }

        return $str;
    }

    /**
     * Verifica se está no padrão UTF-8
     *
     * @param str $str
     * @todo descobrir pq não funciona mb_check_encoding
     */
    private function _check_utf8($str) {
        $len = strlen($str);
        for($i = 0; $i < $len; $i++){
            $c = ord($str[$i]);
            if ($c > 128) {
                if (($c > 247)) return false;
                elseif ($c > 239) $bytes = 4;
                elseif ($c > 223) $bytes = 3;
                elseif ($c > 191) $bytes = 2;
                else return false;
                if (($i + $bytes) > $len) return false;
                while ($bytes > 1) {
                    $i++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191) return false;
                    $bytes--;
                }
            }
        }
        return true;
    } // end of check_utf8

    static function isEmail($email) {
        if(!preg_match('/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/',$email))
            return false;
        return true;
    }

    private function _extractText($html)
    {
        $text = str_replace("\n", '', $html);
        $text = str_replace("<br>", "\n", $text);
        $text = str_replace("<br/>", "\n", $text);
        $text = str_replace("<br />", "\n", $text);
        $text = str_replace("<p>", "\n", $text);
        $text = str_replace("</p>", "\n\n", $text);

        return strip_tags($text);
    }
}