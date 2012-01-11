<?php
/**
 * Calcula o tempo da data até a hora atual
 *
 * @category   RW
 * @package    RW_View
 * @subpackage Helper
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 *
 * @uses viewHelper Zend_View_Helper
 */
class RW_View_Helper_GetTime extends Zend_View_Helper_Abstract
{

    /**
     * Imprime a data no formato correto
     *
     * @param date $d data a ser impressa
     * @param string $format Formatdo para apresentar a data
     *
     */
    public function getTime($d)
    {
        $now = time();
        $date = new Zend_Date($d, Zend_Date::ISO_8601);
        $time = $now - $date->getTimestamp();

        if ($time < 60) {
            $time .= ' segundos';
        } elseif ($time < 3600) { //60*60
            $time = round(floatval($time) / 60) . ' minutos';
        } elseif ($time < 86400) { //60*60*24
            $time = round(floatval($time) / 3660) . ' horas';
        } elseif ($time < 604800) { //60*60*24*30
            $time = round(floatval($time) / 86400) . ' dias';
        } elseif ($time > 1209600 && $time <= 2592000) { //60*60*24*(7*4)
            $time = round(floatval($time) / 604800) . ' semanas';
        } elseif ($time > 2592000 && $time <= 5184000) { //60*60*24*30 && 60*60*24*30*2
            $time = round(floatval($time) / 2592000) . ' mês';
        } elseif ($time > 5184000 && $time < 31104000) { //60*60*24*30*2  && 60*60*24*30*12
            $time = round(floatval($time) / 2592000) . ' meses';
        } else {//if ($time < 31104000) { //60*60*24*30*12
            $time = ' mais de um ano (' . $date->toString() . ')';
        }
        return $time;
    }
}
