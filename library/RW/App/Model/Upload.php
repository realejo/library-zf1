<?php
/**
 * Model com acesso ao BD, Cache e Paginator padronizado.
 * Também permite que tenha acesso ao Loader
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_App_Model_Upload
{

    /**
     * Retorna a pasta de upload para o model baseado no nome da classe
     * Se a pasta não existir ela será criada
     *
     * @param string $class Nome da classe a ser usada
     *
     * @return string
     */
    static public function getUploadPath($class = '')
    {
        // Define a pasta de upload
        $uploadPath = self::getUploadRoot() . '/' . str_replace('_', '/', strtolower($class));

        // Verifica se a pasta do cache existe
        if (!file_exists($uploadPath)) {
            $oldumask = umask(0);
            mkdir($uploadPath, 0777, true);
            umask($oldumask);
        }

        // Retorna a pasta de upload
        return $uploadPath;
    }

    /**
     * Retorna a pasta de visualizacao para o model baseado no nome da classe
     * Se a pasta não existir ela será criada
     *
     * @param string $class Nome da classe a ser usada
     *
     * @return string
     */
    static public function getUrlPath($class = '')
    {
        // Define a pasta de upload
        $urlPath = self::getUrlRoot() . '/' . str_replace('_', '/', strtolower($class));

        // Verifica se a pasta do cache existe
        if (!file_exists($urlPath)) {
            $oldumask = umask(0);
            mkdir($urlPath, 0777, true);
            umask($oldumask);
        }

        // Retorna a pasta de upload
        return $urlPath;
    }

    /**
     * Retorna a pasta raiz de todos os uploads
     *
     * @return string
     */
    static public function getUploadRoot()
    {
        // Verifica se a pasta de upload existe
        if ( !defined('APPLICATION_DATA')  || realpath(APPLICATION_DATA) == false) {
            throw new Exception('A pasta raiz do data não está definido em APPLICATION_DATA em RW_App_Model_Upload::getUploadRoot()');
        }

        // retorna a pasta raiz do cache
        return APPLICATION_DATA . '/upload';
    }

    /**
     * Retorna a pasta raiz do public de todos os uploads
     *
     * @return string
     */
    static public function getUrlRoot()
    {
        // Verifica se a pasta de upload existe
        if ( !defined('APPLICATION_DATA')  || realpath(APPLICATION_DATA) == false) {
            throw new Exception('A pasta raiz do data não está definido em APPLICATION_DATA em RW_App_Model_Upload::getUploadRoot()');
        }

        // retorna a pasta raiz do cache
        return APPLICATION_DATA . '/assets';
    }

}