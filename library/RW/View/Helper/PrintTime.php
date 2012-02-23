<?php
/**
 * Imprime as horas a partir dos segundos
 *
 * @category   RW
 * @package    RW_View
 * @subpackage Helper
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 *
 * @uses RW_Time
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
