<?php
/**
 * Funcionalidades Básicas
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_DNS
{
    /**
     * Substituto do gethostbyname() para procurar pelo DSN sem usar o cache do PHP
     *
     * @param string  $host    IP ou endereço a ser pesquisado
     * @param int     $timeout OPCIONAL. Tempo máximo de procura
     * @return string
     */
    static function getAddrByHost($host, $timeout = 3)
    {
        $query = `nslookup -timeout=$timeout -retry=1 $host`;
        if (preg_match('/\nAddress: (.*)\n/', $query, $matches)) return trim($matches[1]);
        return $host;
    }
}
