<?php
/**
 * Funções matémáticas que não existem módulo stats do PHP
 *
 * @category   RW
 * @package    RW_Math
 * @author     Realejo $Author$
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.relaejo.com.br)
 *
 * @see http://rubsphp.blogspot.com/2011/06/funcoes-estatisticas-em-php.html
 */
class RW_Math
{
    /**
     * Retorna o valor que mais aparece no vetor (moda estatistica)
     *
     * @param array $a Vetor de valores
     * @param int $quantidade Quantidade de vezes que a moda foi observada
     * @return array Valores mais observados no vetor
     */
    static function moda(array $a, &$quantidade = 0) {
        $moda = array();
        if (empty($a)) {
            return $moda;
        }

        // Calcular quantidade de ocorrencias de cada valor
        $ocorrencias = array();
        foreach ($a as $valor) {
            $valor_str = var_export($valor, true);
            if (!isset($ocorrencias[$valor_str])) {
                $ocorrencias[$valor_str] = array(
                    'valor'       => $valor,
                    'ocorrencias' => 0
                );
            }
            $ocorrencias[$valor_str]['ocorrencias'] += 1;
        }

        // Determinar maior ocorrencia
        $quantidade = null;
        foreach ($ocorrencias as $item) {
            if ($quantidade === null || $item['ocorrencias'] >= $quantidade) {
                $quantidade = $item['ocorrencias'];
            }
        }

        // Obter valores com a maior ocorrencia
        foreach ($ocorrencias as $item) {
            if ($item['ocorrencias'] == $quantidade) {
                $moda[] = $item['valor'];
            }
        }
        return $moda;
    }

    /**
     * Obtem a mediana de um vetor de numeros.
     * @param array $a Vetor de numeros
     * @param callback $comparacao Funcao de comparacao para ordenar o vetor (ou null para usar a funcao sort para ordenar)
     * @return number || bool Mediana do vetor ou false, caso seja passado um vetor vazio
     */
    static function mediana(array $a, $comparacao = null) {
        if (empty($a)) {
            return false;
        }

        // Ordenar o vetor
        if ($comparacao === null) {
            sort($a);
        } else {
            usort($a, $comparacao);
        }

        $tamanho = count($a);

        // Tamanho impar: obter valor mediano
        if ($tamanho % 2) {
            $mediana = $a[(($tamanho + 1) / 2) - 1];

        // Tamanho par: obter a media simples entre os dois valores medianos
        } else {
            $v1 = $a[($tamanho / 2) - 1];
            $v2 = $a[$tamanho / 2];
            $mediana = ($v1 + $v2) / 2;
        }
        return $mediana;
    }

}