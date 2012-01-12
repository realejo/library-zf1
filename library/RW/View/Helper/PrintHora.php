<?php 
/**
 * Calcula o tempo em horas e retorna no formado HH:MM
 *
 * @category   RW
 * @package    RW_View
 * @subpackage Helper
 * @author     Realejo
 * @version    $Id: GetDays.php 7 2012-01-11 17:15:57Z rodrigo $
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 *
 * @uses viewHelper Zend_View_Helper
 */
class RW_View_Helper_PrintHora extends Zend_View_Helper_Abstract
{
    /**
     * @param date $d data a ser comparada
     */
    public function PrintHora($d) {
    	
    	if(empty($d)){
    		return null;
    	}else{
	    	$hora = 0;
	    	$mHora   = 0;
	    	$minutos = 0;
	    	for($m = 1; $m<= $d;$m){
	    		$mHora++;
	    		$minutos++;
	    		if($mHora == 60){
	    			$hora++;
	    			$mHora = 0;
	    			$minutos = 0;
	    		}
	    	}
	    	return $mHora.":".$minutos;
    	}
    }
}
?>