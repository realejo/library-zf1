<?php

declare(strict_types=1);

/**
 * Gerenciador de cache utilizado pelo App_Model
 *
 * Ele cria automaticamente a pasta de cache, dentro de data/cache, baseado no nome da classe
 */
class RW_App_Model_Cache
{

    /**
     * Configura o cache
     *
     * @return Zend_Cache_Core
     */
    static public function getFrontend($class = '')
    {
        // Configura o cache
        $frontendOptions = ['automatic_serialization' => true, 'lifetime'                => 86400];
        $backendOptions = ['cache_dir' => self::getCachePath($class)];
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

        return $cache;
    }

    /**
     * Apaga o cache de consultas do model
     */
    static public function clean()
    {
        // Apaga o cache
        self::getFrontend()->clean();
    }

    /**
     * Retorna a pasta raiz de todos os caches
     *
     * @return string
     */
    static public function getCacheRoot(): string
    {
        // Verifica se a pasta de cache existe
        if (! defined('APPLICATION_DATA')) {
            throw new Exception('A pasta raiz do data  não está definido em APPLICATION_DATA em RW_App_Model_Cache::getCacheRoot()');
        }

        if (! is_dir(APPLICATION_DATA) || ! is_writable(APPLICATION_DATA)) {
            throw new Exception("A pasta raiz do data(APPLICATION_DATA) '" . APPLICATION_DATA . "' não existe ou não tem permissão de escrita em RW_App_Model_Cache::getCacheRoot()'");
        }

        // Verifica se a pasta do cache existe
        $cachePath = APPLICATION_DATA . '/cache';
        if (! file_exists($cachePath)) {
            $oldumask = umask(0);
            mkdir($cachePath, 0777, true); // or even 01777 so you get the sticky bit set
            umask($oldumask);
        }

        // retorna a pasta raiz do cache
        return $cachePath;
    }

    /**
     * Retorna a pasta de cache para o model baseado no nome da classe
     * Se a pasta não existir ela será criada
     *
     * @param string $class Nome da classe a ser usada
     *
     * @return string
     */
    public static function getCachePath(string $class = '')
    {
        // Define a pasta de cache
        $cachePath = self::getCacheRoot() . '/' . str_replace('_', '/', strtolower($class));

        // Verifica se a pasta do cache existe
        if (! file_exists($cachePath)) {
            $oldumask = umask(0);
            mkdir($cachePath, 0777, true); // or even 01777 so you get the sticky bit set
            umask($oldumask);
        }

        // Retorna a pasta de cache
        return $cachePath;
    }

    /**
     * Ignora o backend e apaga os arquivos do cache.
     * inclui as subpastas.
     * Serão removidos apenas os arquivos de cache e não as pastas.
     *
     * @param string $path
     */
    static public function completeCleanUp($path)
    {
        if (is_dir($path)) {
            $results = scandir($path);
            foreach ($results as $result) {
                if ($result === '.' or $result === '..')
                    continue;

                if (is_file($path . '/' . $result)) {
                    unlink($path . '/' . $result);
                }

                if (is_dir($path . '/' . $result)) {
                    self::completeCleanUp($path . '/' . $result);
                }
            }
        }
    }
}
