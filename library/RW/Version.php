<?php
/**
 * Classe para armazenar e recuperar a versão da biblioteca da Realejo
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_Version
{
    /**
     * Indentificador de versão
     * @see compareVersion()
     */
    public CONST VERSION = '1.4.2';

    /**
     * A ultima versão disponível
     *
     * @var string
     */
    protected static $_latestVersion;

    /**
     * Compara a versão $version com a versão marcada em RW_Version::VERSION
     *
     * @param  string  $version  A version string (e.g. "0.7.1").
     * @return int              -1 if the $version is older,
     *                           0 if they are the same,
     *                           and +1 if $version is newer.
     *
     */
    public static function compareVersion($version)
    {
        $version = strtolower($version);
        $version = preg_replace('/(\d)pr(\d?)/', '$1a$2', $version);
        return version_compare($version, strtolower(self::VERSION));
    }

    /**
     * Recupera a ultima versão disponível
     *
     * @return string
     */
    public static function getLatest()
    {
        if (null === self::$_latestVersion) {
            self::$_latestVersion = 'not available';

            $handle = fopen('https://raw.githubusercontent.com/realejo/library-zf1/master/version', 'r');
            if (false !== $handle) {
                self::$_latestVersion = stream_get_contents($handle);
                fclose($handle);
            }
        }

        return self::$_latestVersion;
    }
}
