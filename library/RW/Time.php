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
    public const HOUR = 'hh';
    public const HOUR_SHORT = 'h';
    public const MINUTE = 'mm';
    public const MINUTE_SHORT = 'm';
    public const SECOND = 'ss';
    public const SECOND_SHORT = 's';
    public const SIGNED = 'S';

    private int $_time = 0;

    public function __construct($time = null, $part = null)
    {
        if ($time instanceof Zend_Date || self::isTime($time)) {
            $this->setTime($time, $part);
        }
    }

    /**
     * Grava o tempo de acordo com o formato
     *
     * @param string|Zend_Date $time
     * @param string $part OPCIONAL Formato informado
     *
     */
    public function setTime($time, $part = null): RW_Time
    {
        // Verifica se é Zend_date
        if ($time instanceof Zend_Date) {
            $time = $time->toString('HH:mm:ss');
            $part = 'h:m:s';
        }

        if (!self::isTime($time)) {
            throw new Exception("Tempo '$time' inválido");
        }

        if (strpos($time, ':') !== false || !empty($part)) {
            if (empty($part)) {
                $part = (substr_count($time, ':') == 1) ? 'm:s' : 'h:m:s';
            } else {
                $part = strtolower($part);
            }

            $aTime = explode(':', $time);
            $aPart = explode(':', $part);
            $h = $m = $s = 0;

            // @todo o que fazer quando $aTime mais informações no aTime que aPart?
            foreach ($aPart as $i => $p) {
                if ($p === self::HOUR || $p === self::HOUR_SHORT) {
                    $h = $aTime[$i];
                } elseif ($p === self::MINUTE || $p === self::MINUTE_SHORT) {
                    $m = $aTime[$i];
                } elseif ($p === self::SECOND || $p === self::SECOND_SHORT) {
                    $s = $aTime[$i];
                }
            }
            $this->_time = $s + 60 * $m + 60 * 60 * $h;
        } else {
            $this->_time = (int)$time;
        }

        return $this;
    }

    public function get(string $part = null): string
    {
        // Separa o tempo
        $s = $this->_time % 60;
        $m = (($this->_time - $s) / 60) % 60;
        $h = ($this->_time - 60 * $m - $s) / (60 * 60);

        // Retorna o part
        switch ($part) {
            case self::SECOND :
                return str_pad($s, 2, '0', STR_PAD_LEFT);
            case self::SECOND_SHORT :
                return (string)$s;
            case self::MINUTE :
                return str_pad($m, 2, '0', STR_PAD_LEFT);
            case self::MINUTE_SHORT :
                return (string)$m;
            case self::HOUR :
                return str_pad($h, 2, '0', STR_PAD_LEFT);
            case self::HOUR_SHORT :
                return (string)$h;
        }

        return '0';
    }


    public function toString(string $format = 'Shh:mm:ss'): string
    {
        $time = $this->_time;

        // Verifica o sinal
        if ($this->_time < 0) {
            $format = str_replace('S', '-', $format);
            $time = -$time;
        } else {
            $format = str_replace('S', '', $format);
        }

        $s = $time % 60;
        $m = (($time - $s) / 60) % 60;
        $h = ($time - 60 * $m - $s) / (60 * 60);

        return str_replace(['ss', 's', 'mm', 'm', 'hh', 'h'],
            [
                str_pad($s, 2, '0', STR_PAD_LEFT),
                $s,
                str_pad($m, 2, '0', STR_PAD_LEFT),
                $m,
                str_pad($h, 2, '0', STR_PAD_LEFT),
                $h
            ],
            $format);
    }


    public function getSeconds(): int
    {
        return $this->_time;
    }

    public function setSeconds(int $seconds): RW_Time
    {
        $this->_time -= $this->get(self::SECOND);
        $this->_time += $seconds;

        return $this;
    }

    public function getMinutes(): float
    {
        return $this->_time / 60.0;
    }

    public function setMinutes(int $minutes): RW_Time
    {
        $this->_time -= $this->get(self::MINUTE) * 60;
        $this->_time += $minutes * 60;

        return $this;
    }

    public function getHours(): float
    {
        return $this->_time / (60 * 60.0);
    }

    public function setHours(int $hours): RW_Time
    {
        $this->_time -= $this->get(self::HOUR) * 60 * 60;
        $this->_time += $hours * 60 * 60;

        return $this;
    }

    /**
     * @param string|RW_Time $time Tempo a ser adicionado
     */
    public function addTime($time, string $part = null): RW_Time
    {
        if (!($time instanceof RW_Time)) {
            $time = new RW_Time($time, $part);
        }

        $this->_time += $time->getSeconds();

        return $this;
    }

    /**
     * Subtrai um tempo
     *
     * @param string|RW_Time $time Tempo a ser subtraído
     * @param string|null $part OPICIONAL caso seja passado um string e não RW_Time
     * @return RW_Time
     */
    public function subTime($time, string $part = null): RW_Time
    {
        if (!($time instanceof RW_Time)) {
            $time = new RW_Time($time, $part);
        }

        $this->_time -= $time->getSeconds();

        return $this;
    }

    public function addSeconds(int $seconds): RW_Time
    {
        $this->_time += $seconds;

        return $this;
    }

    public function subSeconds(int $seconds): RW_Time
    {
        $this->_time -= $seconds;

        return $this;
    }

    public function addMinutes(int $minutes): RW_Time
    {
        $this->_time += $minutes * 60;

        return $this;
    }

    public function subMinutes(int $minutes): RW_Time
    {
        $this->_time -= $minutes * 60;

        return $this;
    }

    public function addHours(int $hours): RW_Time
    {
        $this->_time += $hours * 60 * 60;

        return $this;
    }

    public function subHours(int $hours): RW_Time
    {
        $this->_time -= $hours * 60 * 60;

        return $this;
    }

    public static function isTime(string $time): bool
    {
        $time = trim($time);

        return (preg_match('/^(-?)(\d{1,2}):(\d{1,2}):(\d{1,2})$/', $time) == 1
            || preg_match('/^(-?)(\d{1,2}):(\d{1,2})$/', $time) == 1
            || preg_match('/^(-?)(\d*)$/', $time) == 1
            || preg_match('/^(-?)(\d*)\.(\d*)$/', $time) == 1
        );
    }

}