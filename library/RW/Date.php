<?php
/**
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_Date extends Zend_Date
{
    public const QUARTER = 'Q';

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
     *
     * @param string|Zend_Date $d data a se transformada para o formato do MYSQL
     * @return string
     */
    static public function toMySQL($d)
    {
        if (empty($d)) {
            return null;
        } else {
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
     *
     * @param Zend_Date $d1
     * @param Zend_Date $d2
     * @param string $part
     * @return int
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
                return floor($diff / 31_536_000); # 60*60*24*365
            case 'm':
                return floor($diff / 2_592_000); # 60*60*24*30
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
     * @return string|int
     */
    public function get($part = null, $locale = null)
    {
        if ($part === 'Q') {
            $q = parent::get('M', $locale);
            return ceil ($q / 3);
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
        return [1  => 'Janeiro', 2  => 'Fevereiro', 3  => 'Março', 4  => 'Abril', 5  => 'Maio', 6  => 'Junho', 7  => 'Julho', 8  => 'Agosto', 9  => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'];
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
        return $meses[$m] ?? null;
    }

    /**
     * Retornar qual é a semana
     *
     * @var $d Array||int
     *
     * @return string;
     */
    static public function getSemana($d = null)
    {
    	$tempData = null;
     // Configura a semana
    	$nome_semana = ['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'];

    	if (is_string($d)) $tempData = strlen($d) > 1 ? $d : (int) $d;

    	// Verifica se foi passado uma data
    	if (is_array($d) || is_string($tempData)) {
    		// Configura a data
    		if (is_array($d)) {
    			$temp = $d['ano']."-".$d['mes']."-".$d['dia'];

    		} else {
    			$temp = self::toMySQL($d);

    		}
    		// converte para a semnana
	        $w = date('w', strtotime($temp));

	        // Retorna qual é a semana
	        return $nome_semana[$w];

	    // Verifica se é um semana
    	} elseif (is_numeric($tempData)) {
    		return $nome_semana[$d] ?? null;

    	} else {
    		// Retorna as semanas
    		return $nome_semana;

    	}
    }

    /**
     * Verifica se é uma string de data válida e retorna o array dela
     *
     * @param string $date
     * @param string $format OPCIONAL
     * @return array|NULL
     */
    static function getData($date, $format = 'dd/MM/yyyy')
    {
        if (!empty($date) && parent::isDate($date, $format)) {
            return Zend_Locale_Format::getDate($date, ['date_format'=>$format]);
        }

        return null;
    }

    static function convertZendDateToDateTime($format = 'dd/MM/yyyy')
    {
        $replaceFormat = [
            'yyyy' => 'Y',
            self::MONTH => '$', // MM
            self::DAY => 'd', // dd
            self::HOUR => 'h', // HH
            self::MINUTE => 'i', // mm
            self::MINUTE_SHORT => 'i', // m
            self::SECOND => 's'
        ];

        $format = str_replace(array_keys($replaceFormat), array_values($replaceFormat), $format);
        return str_replace('$', 'm', $format);
    }

    static public function isDate($date, $format = 'dd/MM/yyyy', $locale = null)
    {
        $dateTime = \DateTime::createFromFormat(self::convertZendDateToDateTime($format), $date, new \DateTimeZone('UTC'));

        // Verifica se apareceu algum erro
        $errors = \DateTime::getLastErrors();
        if (!empty($errors['warning_count'])) {
            return false;
        }
        return $dateTime !== false;
    }
}
