<?php
/**
 * @category   RW
 * @package    RW_Time
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */
class RW_Time
{
    CONST HOUR         = 'hh';
    CONST HOUR_SHORT   = 'h';
    CONST MINUTE       = 'mm';
    CONST MINUTE_SHORT = 'm';
    CONST SECOND 	   = 'ss';
    CONST SECOND_SHORT = 's';

    private $_time = 0;

    public function __construct($time = null, $part = null)
    {
        if (!empty($time)) $this->setTime($time, $part);
    }

    public function setTime($time, $part = null)
    {
        // Verifica se é Zend_date
        if ($time instanceof Zend_Date) {
        	$time = $time->toString('HH:mm:ss');
        	$part = 'h:m:s';
        }

        if (strpos($time, ':') !== false || !empty($part)) {
        	if ( empty($part) ) {
        		$part = (substr_count($time, ':') == 1) ? 'm:s' : 'h:m:s';
        	} else {
        		$part = strtolower($part);
        	}

        	$aTime = explode(':', $time);
        	$aPart = explode(':', $part);
        	$h = $m = $s = 0;

        	// @todo o que fazer quando $aTime mais informações no aTime que aPart?
        	foreach ($aPart as $i=>$p) {
        		if ($p == self::HOUR || $p == self::HOUR_SHORT) {
        			$h = $aTime[$i];
        		} elseif ($p == self::MINUTE || $p == self::MINUTE_SHORT) {
        			$m = $aTime[$i];
        		} elseif ($p == self::SECOND || $p == self::SECOND_SHORT) {
        			$s = $aTime[$i];
        		}
        	}

        	$this->_time = $s + 60*$m + 60*60*$h;
        } else {
        	// Considera que é um numero
        	$this->_time = (int) $time;
        }
        return $this;
    }

    /**
     * Retorna apenas um pedaço
     *
     * @param  string $part Pedaço desejado
     * @return string
     */
    public function get($part = null)
    {
        // Separa o tempo
        $s = $this->_time % 60;
        $m = ( ($this->_time - $s)/60 ) % 60;
        $h = ($this->_time - 60*$m - $s) / (60*60);

        //echo "h=$h, $m=$m, s=$s, time=". $this->_time ."\n";

        // Retorna o part
        switch ($part) {
        	case self::SECOND : return str_pad($s, 2, '0', STR_PAD_LEFT);
        	case self::SECOND_SHORT : return (string) $s;
        	case self::MINUTE : return str_pad($m, 2, '0', STR_PAD_LEFT);
        	case self::MINUTE_SHORT : return (string) $m;
        	case self::HOUR : return str_pad($h, 2, '0', STR_PAD_LEFT);
        	case self::HOUR_SHORT : return (string) $h;
        }

        return 0;
    }


    public function toString($format = 'hh:mm:ss')
    {
        $s = $this->_time % 60;
        $m = ( ($this->_time - $s)/60 ) % 60;
        $h = ($this->_time - 60*$m - $s) / (60*60);

        $format = str_replace(array('ss','s','mm','m','hh','h'),
        					  array(
        					 	str_pad($s, 2, '0', STR_PAD_LEFT), $s,
        					 	str_pad($m, 2, '0', STR_PAD_LEFT), $m,
        					 	str_pad($h, 2, '0', STR_PAD_LEFT), $h
        					 ),
        					 $format);
        return $format;
    }


    public function getSeconds()
    {
        return $this->_time;
    }

    public function setSeconds($seconds)
    {
    	$this->_time -= $this->get(RW_Time::SECOND);
    	$this->_time += $seconds;
    	return $this;
    }

    public function getMinutes()
    {
        return  $this->_time/60;
    }

    public function setMinutes($minutes)
    {
    	$this->_time -= $this->get(RW_Time::MINUTE)*60;
    	$this->_time += $minutes*60;
    	return $this;
    }

    public function getHours()
    {
    	return  $this->_time/(60*60);
    }

    public function setHours($hours)
    {
    	$this->_time -= $this->get(RW_Time::HOUR)*60*60;
    	$this->_time += $hours*60*60;
    	return $this;
    }

    public function addTime($time, $part = null)
    {
        $time = new RW_Time($time, $part);
        $this->_time += $time->getSeconds();
        return $this;
    }

    public function subTime($time, $part = null)
    {
        $time = new RW_Time($time, $part);
        $this->_time -= $time->getSeconds();
        return $this;
    }

    public function addSeconds($seconds)
    {
        $this->_time += $seconds;
        return $this;
    }

    public function subSeconds($seconds)
    {
        $this->_time -= $seconds;
        return $this;
    }

    public function addMinutes($minutes)
    {
        $this->_time += $minutes*60;
        return $this;
    }

    public function subMinutes($minutes)
    {
        $this->_time -= $minutes*60;
        return $this;
    }

    public function addHours($hours)
    {
        $this->_time += $hours*60*60;
        return $this;
    }

    public function subHours($hours)
    {
        $this->_time -= $hours*60*60;
        return $this;
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

}