<?php
/**
 * Verifica os spams mais comuns e se passar verifica se é um SPAM no AKISMET
 *
 * @category   RW
 * @package    RW_Spam
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 *
 * @uses       Zend_Service_Akismet
 */
class RW_Spam
{
    const API_KEY = '17f998c89b39';
    const BLOG    = 'http://www.pinmeup.com.br/';

    public static function isSpam($name, $url, $message)
    {
        if ($this->chkTrigger($message)) {
            return true;
        } else {

            //  Verifica se é SPAM
            $akismet = new Zend_Service_Akismet(self::API_KEY, self::BLOG );

            $data = array(
                'user_ip'               => $_SERVER["REMOTE_ADDR"],
                'user_agent'            => $_SERVER["HTTP_USER_AGENT"],
                'comment_type'          => 'comment',
                'comment_author'        => $name,
                'comment_author_url'    => $url,
                'comment_content'       => $message
            );

            return $akismet->isSpam($data);
        }
    }

    public function chkTrigger($texto) {
    	$tagsLight = array('porn', 'fuckin', 'bondage', 'masturbation', 'mastuberting','lolita', 'guestbook', 'free',
    					   'rubias', 'fetish', 'pennis', 'hentai', 'blowjob', 'wrestling', 'prescription',
    	                   'online', 'nude', 'hazzard', 'windows', 'wedding', 'free', 'nursing', 'sex', 'hosting',
    	                   'htaccess', 'naked', 'casino', 'http', 'ready', 'txt', 'insurance', 'adult');
    	$tagsMedium = array('lesbian', 'nipples');
    	$tagsHard = array('cialis', 'viagra', 'levitra', 'diazepam', 'xanax', 'meridia', 'propecia', 'phentermine', 'xxx',
    					  '<script>', 'metabo', 'acomplia', 'wellbutrin', 'href', 'url', 'drunk girl', 'redtube', 'comment', 'gangbang');
    	$spamTrigger = 5;
    	$spamLevel = 0;
    	$resultado = false;

    	if (!is_null($texto) && $texto != '') {
    	    $texto = strtolower($texto);

			for ($t=0;$t<count($tagsHard);$t++) {
				$pos = 0;
				while ($pos < strlen($texto)) {
					$p = strpos($texto, $tagsHard[$t], $pos);
					if ($p) {
						$spamLevel = $spamLevel + 3;
						$pos = $p+1;
					} else {
						break;
					}

					// Se chegar no limite nem continua
					if ($spamLevel >= $spamTrigger) {
						break 2;
					}
				}
			}

			if ($spamLevel < $spamTrigger) {
			    for ($t=0;$t<count($tagsMedium);$t++) {
    			    $pos = 0;
    				while ($pos < strlen($texto)) {
    					$p = strpos($texto, $tagsMedium[$t], $pos);
    					if ($p !== false) {
    						$spamLevel = $spamLevel + 2;
    						$pos = $p+1;
    					} else {
    						break;
    					}

    					// Se chegar no limite nem continua
    					if ($spamLevel >= $spamTrigger) {
    						break 2;
    					}
    				}
			    }
			}

			if ($spamLevel < $spamTrigger) {
			    for ($t=0;$t<count($tagsLight);$t++) {
    			    $pos = 0;
    				while ($pos < strlen($texto)) {
    					$p = strpos($texto, $tagsLight[$t], $pos);
    					if ($p !== false) {
    						$spamLevel = $spamLevel + 1;
    						$pos = $p+1;
    					} else {
    						break;
    					}

    					// Se chegar no limite nem continua
    					if ($spamLevel >= $spamTrigger) {
    						break 2;
    					}
    				}
			    }
			}
    	}

    	return ($spamLevel >= $spamTrigger);
    }
}
