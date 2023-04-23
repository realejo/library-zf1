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
    public function getDays(string $d)
    {
        $now = strtotime('today');
        $date = new Zend_Date($d, Zend_Date::ISO_8601);
        $time = $now - $date->getTimestamp();

        if ($time < 86400) { //60*60*24
            $time = 'hoje';
        } elseif ($time < 172800) { //60*60*24*2
            $time = 'ontem';
        } elseif ($time < 604800) { //60*60*24*30
            $time = 'há ' . round($time / 86400) . ' dias';
        } elseif ($time < 1_209_600) { //60*60*24*(7*4)
            $time = 'há 1 semana';
        } elseif ($time >= 1_209_600 && $time < 2_592_000) { //60*60*24*(7*4)
            $time = 'há ' . round($time / 604800) . ' semanas';
        } elseif ($time >= 2_592_000 && $time < 5_184_000) { //60*60*24*30 && 60*60*24*30*2
            $time = 'há 1 mês';
        } elseif ($time >= 5_184_000 && $time < 31_104_000) { //60*60*24*30*2  && 60*60*24*30*12
            $time = 'há ' . round($time / 2_592_000) . ' meses';
        } else {//if ($time < 31104000) { //60*60*24*30*12
            $time = 'há mais de um ano (' . $date->toString() . ')';
        }
        return $time;
    }
}
