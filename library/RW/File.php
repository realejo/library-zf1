<?php
/**
 *
 * @category   RW
 * @package    RW_File
 * @author     Realejo $Author$
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.relaejo.com.br)
 */
class RW_File
{
    /**
     * Read a file and display its content chunk by chunk
     *
     * @param string  $filename
     * @param bool    $retbytes
     * @param int     $chunk     Size (in bytes) of tiles chunk (1024*1024 = 1048576)
     */
    static public function readfile_chunked($filename, $retbytes = TRUE, $chunk = 1048576) {
        define('CHUNK_SIZE', 1024*1024); //

        $buffer = '';
        $cnt =0;

        $handle = fopen($filename, 'rb');
        if ($handle === false) return false;

        while (!feof($handle)) {
            $buffer = fread($handle, $chunk);
            echo $buffer;
            ob_flush();
            flush();
            if ($retbytes) $cnt += strlen($buffer);
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt; // return num. bytes delivered like readfile() does.
        }
        return $status;
    }
}