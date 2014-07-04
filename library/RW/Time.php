<?php
/**
 * Trabalha com horas independente do dia
 *
 * @todo o que fazer com tempo negativo? o que deve retornar no get()?
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_Time
{
    CONST HOUR         = 'hh';
    CONST HOUR_SHORT   = 'h';
    CONST MINUTE       = 'mm';
    CONST MINUTE_SHORT = 'm';
    CONST SECOND 	   = 'ss';
    CONST SECOND_SHORT = 's';
    CONST SIGNED       = 'S';

    private $_time = 0;

    public function __construct($time = null, $part = null)
    {
        if (self::isTime($time) || $time instanceof Zend_Date) $this->setTime($time, $part);
    }

    /**
     * Grava o tempo de acordo com o formato
     *
     * @param string $time
     * @param string $part OPCIONAL Formato informado
     *
     */
    public function setTime($time, $part = null)
    {
        // Verifica se é Zend_date
        if ($time instanceof Zend_Date) {
        	$time = $time->toString('HH:mm:ss');
        	$part = 'h:m:s';
        }

        if (!self::isTime($time)) {
            throw new Exception("Tempo '$time' inválido");

        } elseif (strpos($time, ':') !== false || !empty($part)) {
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
     * Retorna apenas um pedaço do tempo
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


    /**
     * Retorna o tempo formatado
     *
     * @param string $format OPCIONAL formato a ser retornado
     * @return string
     */
    public function toString($format = 'Shh:mm:ss')
    {
        // Define o tempo a ser usado
        $time = $this->_time;

        // Verifica o sinal
        if ($this->_time < 0) {
            $format = str_replace('S', '-', $format);
            $time = -$time;
        } else {
            $format = str_replace('S', '', $format);
        }

        // Calcula os tempos
        $s = $time % 60;
        $m = ( ($time - $s)/60 ) % 60;
        $h = ($time - 60*$m - $s) / (60*60);

        // Imprime o formato escolhido
        $format = str_replace(array('ss','s','mm','m','hh','h'),
        					  array(
        					 	str_pad($s, 2, '0', STR_PAD_LEFT), $s,
        					 	str_pad($m, 2, '0', STR_PAD_LEFT), $m,
        					 	str_pad($h, 2, '0', STR_PAD_LEFT), $h
        					 ),
        					 $format);

        // Retorna a hora formatada
        return $format;
    }


    /**
     * Retorna o total de segundos do tempo
     *
     * @return time
     */
    public function getSeconds()
    {
        return $this->_time;
    }

    /**
     * Grava os segundos do tempo
     *
     * @param int $seconds
     * @return RW_Time
     */
    public function setSeconds($seconds)
    {
    	$this->_time -= $this->get(RW_Time::SECOND);
    	$this->_time += $seconds;

        // Retorna o RW_Time para manter a cadeia
        return $this;
    }

    /**
     * Recupera o total de minutos
     *
     * @return int
     */
    public function getMinutes()
    {
        return  $this->_time/60;
    }

    /**
     * Grava os minutos do tempo
     *
     * @param int $minutes
     * @return RW_Time
     */
    public function setMinutes($minutes)
    {
    	$this->_time -= $this->get(RW_Time::MINUTE)*60;
    	$this->_time += $minutes*60;

        // Retorna o RW_Time para manter a cadeia
        return $this;
    }


    /**
     * Retorna o total de horas do tempo
     *
     * @return int
     */
    public function getHours()
    {
    	return  $this->_time/(60*60);
    }

    /**
     * Grava as horas no tempo
     *
     * @param int $hours
     * @return RW_Time
     */
    public function setHours($hours)
    {
    	$this->_time -= $this->get(RW_Time::HOUR)*60*60;
    	$this->_time += $hours*60*60;

        // Retorna o RW_Time para manter a cadeia
        return $this;
    }

    /**
     * Adiciona um tempo
     *
     * @param string|RW_Time $time Tempo a ser adicionado
     * @param string $part         OPICIONAL caso seja passado um string e não RW_Time
     * @return RW_Time
     */
    public function addTime($time, $part = null)
    {
         // Verifica se é um objeto RW_Time
        if (!($time instanceof RW_Time) )
            $time = new RW_Time($time, $part);

        // Adicina o tempo
        $this->_time += $time->getSeconds();

        // Retorna o RW_Time para manter a cadeia
        return $this;
    }

    /**
     * Subtrai um tempo
     *
     * @param string|RW_Time $time Tempo a ser subtraído
     * @param string $part         OPICIONAL caso seja passado um string e não RW_Time
     * @return RW_Time
     */
    public function subTime($time, $part = null)
    {
        // Verifica se é um objeto RW_Time
        if (!($time instanceof RW_Time) )
            $time = new RW_Time($time, $part);

        // Subtrai o tempo
        $this->_time -= $time->getSeconds();

        // Retorna o RW_Time para manter a cadeia
        return $this;
    }

    /**
     * Adiciona segundos
     *
     * @param int $seconds
     * @return RW_Time
     */
    public function addSeconds($seconds)
    {
        // Adiciona os segundos
        $this->_time += $seconds;

        // Retorna o RW_Time para manter a cadeia
        return $this;
    }

    /**
     * Subtrai segundos
     *
     * @param int $seconds
     * @return RW_Time
     */
    public function subSeconds($seconds)
    {
        // Subtrai os segundos
        $this->_time -= $seconds;

        // Retorna o RW_Time para manter a cadeia
        return $this;
    }

    /**
     * Adiciona minutes
     *
     * @param int $minutes
     * @return RW_Time
     */
    public function addMinutes($minutes)
    {
        // Adiciona os minutos passados
        $this->_time += $minutes*60;

        // Retorna o RW_Time para manter a cadeia
        return $this;
    }

    /**
     * Subtrai minutos
     *
     * @param int $minutes
     * @return RW_Time
     */
    public function subMinutes($minutes)
    {
        // Subtrai os minutos passados
        $this->_time -= $minutes*60;

        // Retorna o RW_Time para manter a cadeia
        return $this;
    }

    /**
     * Adiciona horas
     *
     * @param int $hours
     * @return RW_Time
     */
    public function addHours($hours)
    {
        // Adiciona as horas passadas
        $this->_time += $hours*60*60;

        // Retorna o RW_Time para manter a cadeia
        return $this;
    }

    /**
     * Subtrai horas
     *
     * @param int $hours
     * @return RW_Time
     */
    public function subHours($hours)
    {
        // Subtrai as horas passadas
        $this->_time -= $hours*60*60;

        // Retorna o RW_Time para manter a cadeia
        return $this;
    }

    /**
     * Valida se o tempo
     *
     * @param string $time
     * @return boolean
     */
    public static function isTime($time)
    {
        // Remove espaços caso haja
        $time = trim($time);

        // Verifica se é valida
        return (preg_match('/^(-?)(\d{1,2}):(\d{1,2}):(\d{1,2})$/', $time) == 1
        		|| preg_match('/^(-?)(\d{1,2}):(\d{1,2})$/', $time) == 1
        		|| preg_match('/^(-?)(\d*)$/', $time) == 1
        		|| preg_match('/^(-?)(\d*)\.(\d*)$/', $time) == 1
        );
    }

}