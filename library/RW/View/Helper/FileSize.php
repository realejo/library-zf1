<?php
/**
 * Imprime o tamanho do arquivo
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
class RW_View_Helper_FileSize extends Zend_View_Helper_Abstract
{
    /**
     * @param int $bytes
     * @param int $precision
     */
    public function fileSize($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
