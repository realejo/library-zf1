<?php
/**
 * Imprime o tamanho do arquivo
 *
 * @uses viewHelper Zend_View_Helper
 * @author Rodrigo
 * @version
 *
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
