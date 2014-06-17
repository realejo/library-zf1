<?php
/**
 * Resumo helper
 *
 * @link      http://github.com/realejo/library-zf1
 * @copyright Copyright (c) 2011-2014 Realejo Design Ltda. (http://www.realejo.com.br)
 */
class RW_View_Helper_Resumo extends Zend_View_Helper_Abstract
{
    /**
     * Reduz o texto eliminando o html
     *
     * @param  string	$texto	Texto a ser resumido
     * @param  int		$size	Número de caracteres máximo
     *
     * @return string
     */
    public function resumo($texto, $size = 200)
    {
        $texto = html_entity_decode(strip_tags($texto),null,'UTF-8');
        $resumo = $texto;

        if (strlen($texto) > $size) {
            $resumo = substr($texto, 0, $size);
            $resumo = substr($resumo, 0, strripos($resumo, ' ' )) . '...';
        }
        return $resumo;
    }
}
