<?php
/**
 * Twitterify
 *
 * @category   RW
 * @package    RW_View
 * @author     Realejo
 * @version    $Id: Twitterify.php 31 2012-05-14 18:14:30Z rodrigo $
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */
class RW_View_Helper_Twitterify extends Zend_View_Helper_Abstract
{
    /**
     * Formata um tweet
     *
     * @param string $ret data a ser comparada
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
