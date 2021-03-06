<?php
/**
 * Imprime a data no formato correto
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_View_Helper_PrintDate extends Zend_View_Helper_Abstract
{
    /**
     * Imprime a data no formato correto
     *
     * @param date $d data a ser impressa
     * @param string $format Formato para apresentar a data
     *
     * @return string
     */
    public function printDate($d = null, $format = "dd/MM/yyyy")
    {
        // Verifica se alguma data foi passada
        if (empty($d)|| is_null($d)) return '';

        // Verifica se a data já está no formato com barras dd/MM/yyyy
        if (strpos($d, '/') !== false) {
            $temp = explode('/', $d);
            $d = $temp[2].'-'.$temp[1].'-'.$temp[0];
        }

        // Cria a data no Zend_Date
        $date = new Zend_Date($d, Zend_Date::ISO_8601);

        // Verifica se é um foramto prédefinido
        switch($format) {
            case 'complete':
                $format = 'dd/MM/yyyy HH:mm:ss';
        }

        // Retorna a data formatada
        return $date->toString($format);
    }
}
