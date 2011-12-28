<?php
/**
 *
 * @author Rodrigo
 * @version
 */


/**
 * Resumo helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class RW_View_Helper_Resumo extends Zend_View_Helper_Abstract
{

	/**
	 * Reduz o texto eliminando o html
	 *
	 * @param string $texto texto a ser resumido
	 * @param int $size número de caracteres máximo
	 *
	 */
	public function resumo($texto, $size = 200) {
		$texto = html_entity_decode(strip_tags($texto),null,'UTF-8');
		$resumo = $texto;

		if (strlen($texto) > $size) {
			$resumo = substr($texto, 0, $size);
			$resumo = substr($resumo, 0, strripos($resumo, ' ' )) . '...';
		}
		return $resumo;
	}
}
