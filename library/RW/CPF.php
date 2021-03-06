<?php
/**
 * Valida e formata o CPF
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_CPF
{
    /**
     * Verifica se o CPF é valido
     *
     * @param string   $cpf CPF com ou sem formatação
     * @return boolean
     */
    static function valid($cpf)
    {
        $cpf = self::unformat($cpf);

        if (strlen($cpf) != 11 ||
            $cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999') return false;

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * Formata o CPF no padrão 000.000.000-00
     *
     * @param string $cpf CPF com ou sem formatação
     * @return string
     */
    static function format($cpf)
    {
        if ( !empty($cpf) ) {
            $cpf = self::unformat($cpf);
            $cpf = substr($cpf,0,3) . '.' . substr($cpf,3,3) . '.' . substr($cpf,6,3) . '-' . substr($cpf,9,2);
        }
        return $cpf;

    }

    /**
     * Remove a formatação do CPF, reduzindo a apenas numeros
     *
     * @param string $cpf
     * @return string
     */
    static function unformat($cpf)
    {
        if ( !is_null($cpf) && $cpf != '') {
            $cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);
        } else $cpf = '';
        return $cpf;
    }
}
