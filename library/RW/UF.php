<?php
/**
 * Classe para gerenciamente de UFs e Regiões geográficas
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_UF
{
    public $uf = ['AC' => 'Acre', 'AL' => 'Alagoas', 'AM' => 'Amazonas', 'AP' => 'Amapá', 'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo', 'GO' => 'Goiás', 'MA' => 'Maranhão', 'MS' => 'Mato Grosso do Sul', 'MT' => 'Mato Grosso', 'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná', 'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte', 'RS' => 'Rio Grande do Sul', 'RO' => 'Rondonia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina', 'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'];

    /**
     * Regioes geograficas
     */
    public $regioes = ['CO' => 'Centro-Oeste', 'NO' => 'Norte', 'NE' => 'Nordeste', 'SE' => 'Sudeste', 'SU' => 'Sul'];

    public $UFRegiao = ['AO' => 'EX', 'PT' => 'EX', 'CH' => 'EX', 'AC' => 'NO', 'AL' => 'NE', 'AM' => 'NO', 'AP' => 'NO', 'BA' => 'NE', 'CE' => 'NE', 'DF' => 'CO', 'ES' => 'SE', 'GO' => 'CO', 'MA' => 'NE', 'MS' => 'CO', 'MT' => 'CO', 'MG' => 'SE', 'PA' => 'NO', 'PB' => 'NE', 'PR' => 'SU', 'PE' => 'NE', 'PI' => 'NE', 'RJ' => 'SE', 'RN' => 'NE', 'RS' => 'SU', 'RO' => 'NO', 'RR' => 'NO', 'SC' => 'SU', 'SP' => 'SE', 'SE' => 'NE', 'TO' => 'NO'];

    /**
     * Retorna as Ufs
     *
     * @param array|string $regiao Região geográfica, se informado retorna apenas as UFs da região
     *
     * @return array
     */
    static public function getUFs($regiao = null)
    {
        $uf = new RW_UF();
        if (is_null($regiao)) {
            return $uf->uf;
        } else {
            $ufs = [];
            if (!is_array($regiao)) $regiao = [$regiao];
            foreach ($regiao as $r) {
                foreach ($uf->uf as $u=>$nome) {
                    if (isset($uf->UFRegiao[$u]) && $uf->UFRegiao[$u] == $r) {
                        $ufs[$u] = $nome;
                    }
                }
            }
            return $ufs;
        }
    }

    /**
     * Retorna as regiões geográficas
     *
     * @return array
     */
    static public function getRegioes()
    {
        $uf = new RW_UF();
        return $uf->regioes;
    }

    /**
     * Retorna as UF e a região a qual pertence
     *
     * @return array
     */
    static public function getUFRegiao()
    {
        $uf = new RW_UF();
        return $uf->UFRegiao;
    }

    /**
     * Retorna todos os Países
     *
     * @return array
     */
    static public function getPaises()
    {
        $uf = new RW_UF();
        return $uf->ufExterior;
    }

}
