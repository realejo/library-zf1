<?php
/**
 * Imprime as horas a partir dos segundos
 *
 * @link      http://github.com/realejo/library-zf1
 * @copyright Copyright (c) 2011-2014 Realejo Design Ltda. (http://www.realejo.com.br)
 */
class RW_View_Helper_PrintTime extends Zend_View_Helper_Abstract
{

    /**
     * Imprime um tempo
     *
     * @param string|int|RW_Time $segundos Segundos ou objeto RW_Time
     * @param string      $format    OPCIONAL Formato de saida
     *
     * @return string
     */
    public function printTime($time, $format = null)
    {
        if (!($time instanceof RW_Time)) $time = new RW_Time($time);
        return $time->toString($format);
    }
}
