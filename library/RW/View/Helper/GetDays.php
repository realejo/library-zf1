<?php
/**
 * Calcula o tempo da data até o dia atual
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
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
        //mostrar quantidade de dias até 14 dias
        }  elseif ($time < 604800) { //60*60*24*30
            $time ='há '. round(floatval($time) / 86400) . ' dias';
        //mostrar quantidades de semanas apartir da 2 semana até 1 mes
        } elseif ($time > 1209600 && $time <= 2592000) { //60*60*24*(7*4)
            $time = 'há ' . round(floatval($time) / 604800) . ' semanas';
        } elseif ($time > 2592000 && $time <= 5184000) { //60*60*24*30 && 60*60*24*30*2
            $time = 'há ' . round(floatval($time) / 2592000) . ' mês';
        } elseif ($time > 5184000 && $time < 31104000) { //60*60*24*30*2  && 60*60*24*30*12
            $time = 'há ' . round(floatval($time) / 2592000) . ' meses';
        } else {//if ($time < 31104000) { //60*60*24*30*12
            $time = 'há mais de um ano (' . $date->toString() . ')';
        }
        return $time;
    }
}
