<?php
/**
 *
 * @uses       Zend_Search_Lucene
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_Search extends Zend_Search_Lucene
{
    /**
     * Retorna o indice utilizado verificando se ele já está criado e usando o Stemmer em portugues
     *
     * @param string $index
     *
     * @return Zend_Search_Lucene_Interface
     */
    static public function getIndex($index, $forceCreate = false)
    {

        // define o caminho
        $path = self::getIndexRoot() . '/'. $index;

        // Verifica se o index já esta criado
        if ( $forceCreate ) {
            // Cria o indice
            $index = self::create($path);

        } else {

            // Tenta abrir o indice
            try {
                $index = self::open($path);
            } catch (Exception $e) {
                $index = self::create($path);
            }
        }

        // Associa o stemmer em PT-BR
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(new StandardAnalyzer_Analyzer_Standard_Portuguese());
        return $index;
    }


    /**
     * Reduz o texto das palavras encontradas
     *
     * @param  string $texto   texto com hightling do Zend_Search
     * @param  int    $before  caracteres antes do hightlight
     * @param  int    $after   caracteres após o hightlight
     * @param  int    $max     tamanho máximo do resumo
     * @return string
     *
     * @todo usar regex para pegar a frase onde aparece a palavra
     */
    static public function resumoHighlight($texto, $before = 50, $after = 50, $max = null)
    {
        // remove os <ENTER>
        $texto = str_replace('&#13;', '', $texto);

        $resumo = '';

        $inicio = strpos($texto, '<b style="color:');
        $final = strrpos($texto, '</b>', $inicio)+4;

        $i = $inicio-$before;
        $i = ($i<0)?0:$i;
        $f = $final+$after;
        $f = ($f>strlen($texto))?strlen($texto):$f;

        if ($i>0) $resumo = '...';

        $resumo .= substr($texto, $i, $f-$i);

        if ($f<strlen($texto)-1) $resumo .= '...';

        return self::fixHighlight($resumo);

    }

    static public function fixHighlight($texto) {
        return preg_replace('(b style=)', 'b class="hightlight" data-temp=', $texto);
    }

    static public function simples($str)
    {
        $text = strip_tags($str);

        $text = str_replace('&nbsp;', ' ', $text);

        $pattern = ['/[\n\r\f\t]?/', '/(\s\s)+/'];
        $replacement = ['', ' '];
        $text = preg_replace($pattern, $replacement, $text);

        return trim($text);
    }

    /**
     * Retorna a pasta raiz de todos os caches
     *
     * @return string
     */
    static public function getIndexRoot()
    {
        // Verifica se a pasta de cache existe
        if (defined('APPLICATION_DATA') === false) {
            throw new Exception('A pasta raiz do data não está definido em APPLICATION_DATA em RW_Search::getIndexRoot()');
        }

        // Verifica se a pasta do cache existe
        $indexPath = APPLICATION_DATA . '/indexes';
        if (!file_exists($indexPath)) {
            $oldumask = umask(0);
            mkdir($indexPath, 0777, true); // or even 01777 so you get the sticky bit set
            umask($oldumask);
        }

        // retorna a pasta raiz do cache
        return $indexPath;
    }
}