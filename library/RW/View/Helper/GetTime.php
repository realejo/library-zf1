<?php

/**
 * Calcula o tempo da data até a hora atual
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_View_Helper_GetTime extends Zend_View_Helper_Abstract
{
    /**
     * Imprime a data no formato correto
     *
     * @param string $locale Localização da data
     *
     */
    public function getTime(string $d, string $format = null, $locale = null): string
    {
        // Define o formato
        if (!isset($format)) {
            $format = Zend_Date::ISO_8601;
        } elseif ($format === 'twitter') {
            $format = 'EEE MMM dd HH:mm:ss ZZZ yyyy';
            $locale = 'en_US';
        } elseif ($format === 'fb') {
            $format = 'EEE, MMM dd yyyy HH:mm:ss ZZZ';
            $locale = 'en_US';
        }

        // Cria a data
        $date = new Zend_Date($d, $format, $locale);
        $date->setLocale('pt_BR')->setTimezone('America/Sao_Paulo');
        if ($date->get('Y') === '0') {
            $date->set(date('Y'), 'Y');
        }

        // Calcula a diferença
        $now = time();
        $time = (float)($now - $date->getTimestamp());

        // Formata a hora
        if ($time < 60) {
            $time .= ' segundos';
        } elseif ($time < 3600) { //60*60
            $time = round($time / 60) . ' minutos';
        } elseif ($time < 7200) { //60*60*2
            $time = round($time / 3660) . ' hora';
        } elseif ($time < 86400) { //60*60*24
            $time = round($time / 3660) . ' horas';
        } elseif ($time < 604800) { //60*60*24*30
            $time = round($time / 86400) . ' dias';
        } elseif ($time > 1_209_600 && $time <= 2_592_000) { //60*60*24*(7*4)
            $time = round($time / 604800) . ' semanas';
        } elseif ($time > 2_592_000 && $time <= 5_184_000) { //60*60*24*30 && 60*60*24*30*2
            $time = round($time / 2_592_000) . ' mês';
        } elseif ($time > 5_184_000 && $time < 31_104_000) { //60*60*24*30*2  && 60*60*24*30*12
            $time = round($time / 2_592_000) . ' meses';
        } else {//if ($time < 31104000) { //60*60*24*30*12
            $time = 'mais de um ano (' . $date->toString() . ')';
        }
        return $time;
    }
}
