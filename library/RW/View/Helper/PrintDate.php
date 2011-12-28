<?php
/**
 * Imprime a data no formato correto
 *
 * @uses viewHelper Zend_View_Helper
 * @author Rodrigo
 * @version
 *
 */
class RW_View_Helper_PrintDate extends Zend_View_Helper_Abstract
{

    /**
     * Imprime a data no formato correto
     *
     * @param date $d data a ser impressa
     * @param string $format Formato para apresentar a data
     *
     */
    public function printDate($d = null, $format = "dd/MM/yyyy") {

        if (strpos($d, '/')) {
            $temp = explode('/', $d);
            $d = $temp[2].'-'.$temp[1].'-'.$temp[0];
        }
        if ( !is_null($d) ) {
            $date = new Zend_Date($d, Zend_Date::ISO_8601);

            // formatos predefinidos
            switch($format) {
                case 'complete':
                    $format = 'dd/MM/yyyy HH:mm:ss';
                    break;
            }

            return $date->toString($format);
        } else return null;
    }
}
