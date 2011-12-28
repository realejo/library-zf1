<?php
/**
 * Funcionalidades Básicas
 *
 * @category   RW
 * @package    RW_Base
 * @author     Realejo $Author$
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.relaejo.com.br)
 */
class RW_Base
{
    static public $charTable = array(
 		'À' => 'A',
		'Á' => 'A',
		'Â' => 'A',
		'Ã' => 'A',
		'Ä' => 'A',
		'Å' => 'A',
		'Æ' => 'AE',
		'Ç' => 'C',
		'È' => 'E',
		'É' => 'E',
		'Ê' => 'E',
		'Ë' => 'E',
		'Ì' => 'I',
		'Í' => 'I',
		'Î' => 'I',
		'Ï' => 'I',
		'Ñ' => 'N',
		'Ò' => 'O',
		'Ó' => 'O',
		'Ô' => 'O',
		'Õ' => 'O',
		'Ö' => 'O',
		'Ù' => 'U',
		'Ú' => 'U',
		'Û' => 'U',
		'Ü' => 'U',
		'Ý' => 'Y',
		'Ÿ' => 'Y',
		'à' => 'a',
		'á' => 'a',
		'â' => 'a',
		'ã' => 'a',
		'ä' => 'a',
		'å' => 'a',
		'æ' => 'ae',
		'ç' => 'c',
		'è' => 'e',
		'é' => 'e',
		'ê' => 'e',
		'ë' => 'e',
		'ì' => 'i',
		'í' => 'i',
		'î' => 'i',
		'ï' => 'i',
		'ñ' => 'n',
		'ò' => 'o',
		'ó' => 'o',
		'ô' => 'o',
		'õ' => 'o',
		'ö' => 'o',
		'ù' => 'u',
		'ú' => 'u',
		'û' => 'u',
		'ü' => 'u',
		'ý' => 'y',
		'ÿ' => 'y'
    );

    /**
     * remove os acentos pelas letras correspondetes
     * @param string $subject
     *
     * @return string
     */
    static public function RemoveAcentos($subject)
    {

        foreach (self::$charTable as $search => $replace)
        {
            $subject = str_replace($search, $replace, $subject);
        }

        return $subject;
    }
    /**
     * Strip tags com tags e atributos permitidos
     * @param str $string
     * @param str $allowtags
     * @param str $allowattributes
     * @return str
     */
    static public function strip_tags_attributes($string,$allowtags=NULL,$allowattributes=NULL){
        if($allowattributes){

            if(!is_array($allowattributes))
                $allowattributes = explode(",",$allowattributes);


            echo strripos($string, 'style');
            die();

            foreach($allowattributes as $aa){
            	$rep = '/([^>]*) ('.$aa.')(=)(\'.*\'|".*")/i';
            	$string = preg_replace($rep, '$1 $2_-_-$4', $string);
            	if (preg_match($rep,$string) > 0) {
            		$string = preg_replace($rep, '$1 $2_-_-$4', $string);
            	}
            }
        }

        return strip_tags($string,$allowtags);
    }

    /**
     * remove os caracteres que não podem estar no nome do arquivo
     * @todo remover acentos e não apaga-los
     *
     * @param string $file
     * @return string
     */
    static public function CleanFileName($subject)
    {
        $subject = preg_replace('/\s+/', '_', self::RemoveAcentos(trim($subject)));
        $search  = array( "([\40])" , "([^a-zA-Z0-9-._])", "(-{2,})" );
        $replace = array("-", "", "-");
        return strtolower(preg_replace($search, $replace, $subject));
    }

    static public function cleanInput($string){
        $subject = preg_replace('/\s+/', '_', trim($string));
        $search  = array( "([\40])" , "([^a-zA-Z0-9_])", "(-{2,})" );
        $replace = array("-", "", "-");

        $subject = preg_replace($search, $replace, $subject);
        $subject = str_replace('_', ' ', trim($subject));
        return $subject;


    }

    /**
     *  Transforma uma string em url friendly
	 *
     * @param str $string
     * @param str $space
	 *
	 * @return string
     */
    static public function seourl($string, $space="-") {

        if (function_exists('iconv')) {
            $string = @iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        }

        $string = self::RemoveAcentos(strtolower(trim($string)));
        $string = preg_replace('([_|\s]+)', '-', $string); // change all spaces and underscores to a hyphen
        $string = preg_replace('([^a-z0-9-])', '', $string); // remove all non-numeric characters except the hyphen
        $string = preg_replace('([-]+)', '-', $string); // replace multiple instances of the hyphen with a single instance
        $string = preg_replace('(^-+|-+$)', '', $string); // trim leading and trailing hyphens
        $string = str_replace('-', $space, $string);
        return trim($string);

    }


    /**
     * Extrai o código da url considerando o primeiro hifen ou virgula
     * @param str $string
     * @return string
     */
    static public function getSEOID($url)
    {
        $delimiter = null;
        if (strpos($url,',') && strpos($url,'-')) {
            $delimiter = (strpos($url,',')<strpos($url,'-')) ? ',':'-';

        } elseif (strpos($url,',')) {
            $delimiter = ',';

        } elseif (strpos($url,'-')) {
            $delimiter = '-';
        }

        if (!is_null($delimiter)) {
            if (strpos($url,$delimiter)) {
                $url = explode($delimiter,$url);
                $url = $url[0];
            }
        }

        return $url;
    }

    public static function CleanHTML($html,  $allowable_tags = null)
    {
        if (is_null($html)) return '';

        $texto = strip_tags($html,  $allowable_tags );
        $texto = str_replace('&nbsp;', ' ', $texto);
        $texto = preg_replace('/\n/', ' ', $texto);
        $texto = preg_replace('/\t/', ' ', $texto);
        $texto = preg_replace('/\s\s+/', ' ', $texto);

        // volta os acentos
        $texto = html_entity_decode ( $texto, ENT_COMPAT, 'UTF-8' );

        return trim($texto);
    }

    public static function getCSV($array, $exclude = array(), $labels = array())
    {
        // Recuperar o primeiro registro para ter os nomes dos campos
        $keys = array_keys($array);
        $keys = array_keys($array[$keys[0]]);

        // Verifica os campos a serem excluídos
        if (is_null($exclude)) {
            $exclude = array();
        } elseif (!is_array($exclude)) {
            $exclude = array($exclude);
        }

        // Arruma o array de exclusão
        $temp = array();
        foreach ($exclude as $e) {
            $temp[$e] = $e;
        }

        // Remove as chaves não usadas
        $keys = array_diff($keys,$exclude);

        $exclude = $temp;

        // Coloca as chaves no CSV
        $temp = $keys;
        foreach ($temp as $i=>$k) {
            if (array_key_exists($k, $labels)) $temp[$i] = $labels[$k];
        }
	    $csv = array(mb_strtoupper(implode(';',$temp), 'UTF-8'));

	    // Constroi o CSV
	    foreach($array as $row) {
	        // Remove as chaves não usadas
	        $row = array_diff_key($row, $exclude);

	        // Coloca no CSV
	        $csv[] =  '"'. implode('";"',$row) .'"';
	    }

	    $csv = implode("\n", $csv);

	    return $csv;
    }
}
