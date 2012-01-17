<?php
/**
 * @category   RW
 * @package    RW_Date
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 *
 * @uses       Zend_Date
 */
class RW_Date extends Zend_Date
{
    const QUARTER = 'Q';

    /**
     * Inclusão do formato mysql
     *
     * @param  string              $format  OPTIONAL Rule for formatting output. If null the default date format is used
     * @param  string              $type    OPTIONAL Type for the format string which overrides the standard setting
     * @param  string|Zend_Locale  $locale  OPTIONAL Locale for parsing input
     * @return string
     */
    public function toString($format = null, $type = null, $locale = null)
    {
        if ($format == 'mysql') $format = 'yyyy-MM-dd HH:mm:ss';
        return parent::toString($format, $type, $locale);
    }

    /**
     * Transforma data no formato d/m/a para o formato a-m-d
     * @param string|Zend_Date $d data a se transformada para o formato do MYSQL
     */
    static public function toMySQL($d)
    {
        if(empty($d)){
            return null;
        }else{
            if ($d instanceof Zend_Date) {
                $sql = $d->toString('yyyy-MM-dd HH:mm:ss');
            } else {
                $datetime = explode(' ', $d);
                $date = explode('/', $datetime[0]);
                $sql = sprintf("%04d-%02d-%02d", $date[2], $date[1], $date[0]);

                if (isset($datetime[1])) $sql .= ' ' . $datetime[1];
            }
            return $sql;
        }
    }

    /**
     *
     * Retorna a diferença entre duas datas ($d1-$d2)
     * Sempre calculado a partir da diferença de segundos entre as datas
     *
     * Opções para $part
     *         a - anos
     *         m - meses
     *         w - semanas
     *         d - dias
     *         h - horas
     *         n - minutos
     *         s - segundos (padrão)
     * @param Zend_Date $d1
     * @param Zend_Date $d2
     * @param string $part
     */
    static function diff(Zend_Date $d1, Zend_Date $d2, $part = null)
    {
        if ( $d1 instanceof Zend_Date)
            $d1 = $d1->get(Zend_Date::TIMESTAMP);

        if ( $d2 instanceof Zend_Date)
            $d2 = $d2->get(Zend_Date::TIMESTAMP);

        $diff = $d1 - $d2;

        switch ($part)
        {
            case 'a':
                return floor($diff / 31536000); # 60*60*24*365
            case 'm':
                return floor($diff / 2592000); # 60*60*24*30
            case 'w':
                return floor($diff / 604800); # 60*60*24*7
            case 'd':
                return floor($diff / 86400); # 60*60*24
            case 'h':
                return floor($diff / 3600);  # 60*60
            case 'n':
                return floor($diff / 60);
            case 's':
            default :
                return $diff;
        }
    }

    /**
     * Alterada para incluir Trimestre (Quarter)
     *
     * @param  string              $part    OPTIONAL Part of the date to return, if null the timestamp is returned
     * @param  string|Zend_Locale  $locale  OPTIONAL Locale for parsing input
     * @return string  date or datepart
     */
    public function get($part = null, $locale = null)
    {
        if ($part === 'Q') {
            $q = parent::get('M', $locale);
            return ceil ($q / 4);
        } else {
            return parent::get($part, $locale);
        }
    }


    /**
     * Retorna os nomes dos meses
     *
     * @todo usar o Zend_Locale para recuperar os nomes dos meses
     *
     * @return array
     */
    static function getMeses()
    {
        return array(
            1  => 'Janeiro',
            2  => 'Fevereiro',
            3  => 'Março',
            4  => 'Abril',
            5  => 'Maio',
            6  => 'Junho',
            7  => 'Julho',
            8  => 'Agosto',
            9  => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        );
    }

    /**
     * Retorna o nome de um mês
     *
     * @todo usar o Zend_Locale para recuperar os nomes dos meses
     *
     * @return string
     */
    static function getMes($m)
    {
        // Recupera os meses
        $meses = self::getMeses();

        // Retorna se o mes existir
        return (isset($meses[$m])) ? $meses[$m] : null;
    }
}