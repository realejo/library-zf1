<?php
/**
 * Calcula o tempo da data até o dia atual
 *
 * @category   RW
 * @package    RW_View
 * @subpackage Helper
 * @author     Realejo $Author$
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.relaejo.com.br)
 *
 * @uses viewHelper Zend_View_Helper
 */
class RW_View_Helper_GetDays extends Zend_View_Helper_Abstract
{
    /**
     * @param date $d data a ser comparada
     */
    public function getDays($d) {
        $now = time();
        $date = new Zend_Date($d, Zend_Date::ISO_8601);
        $time = $now - $date->getTimestamp();
        if ($time < 86400) { //60*60*24
            $time = 'hoje';
        } elseif ($time < 172800) { //60*60*24*2
            $time = 'ontem';
        } elseif ($time < 2592000) { //60*60*24*30
            $time = 'há ' . round(floatval($time) / 86400) . ' dias';
        } elseif ($time < 2419200) { //60*60*24*(7*4)
            $time = 'há ' . round(floatval($time) / 16934400) . ' semanas';
        } elseif ($time < 2592000) { //60*60*24*30
            $time = 'há ' . round(floatval($time) / 2592000) . ' mês';
        } elseif ($time < 31104000) { //60*60*24*30*12
            $time = 'há ' . round(floatval($time) / 2592000) . ' meses';
        } else {//if ($time < 31104000) { //60*60*24*30*12
            $time = 'há mais de um ano (' . $date->toString() . ')';
        }
        return $time;
    }
}
