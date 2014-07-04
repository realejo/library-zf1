<?php
/**
 * Twitterify
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_View_Helper_Twitterify extends Zend_View_Helper_Abstract
{
    /**
     * Formata um tweet
     *
     * @param string $ret texto a ser twitterficado
     * @returns string
     */
    public function twitterify($ret)
    {
      $ret = preg_replace('#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#', "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
      $ret = preg_replace('#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#', "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
      $ret = preg_replace('/@(\w+)/', "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
      $ret = preg_replace('/#(\w+)/', "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
      return $ret;
    }
}
