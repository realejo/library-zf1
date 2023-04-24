<?php

declare(strict_types=1);

/**
 * Model com acesso ao BD, Cache e Paginator padronizado.
 * Também permite que tenha acesso ao Loader
 */
class RW_App_Model_Upload
{

    /**
     * Retorna a pasta de upload para o model baseado no nome da classe
     * Se a pasta não existir ela será criada
     *
     * @deprecated Usar RW_Upload
     *
     * @param string $class Nome da classe a ser usada
     *
     * @return string
     */
    static public function getUploadPath($class = '')
    {
        return RW_Upload::getUploadPath($class);
    }

    /**
     * Retorna a pasta de visualizacao para o model baseado no nome da classe
     * Se a pasta não existir ela será criada
     *
     * @deprecated Usar RW_Upload
     *
     * @param string $class Nome da classe a ser usada
     *
     * @return string
     */
    static public function getUrlPath($class = '')
    {
        return RW_Upload::getAssetsReservedPath($class);
    }

    /**
     * Retorna a pasta raiz de todos os uploads
     *
     * @deprecated Usar RW_Upload
     *
     * @return string
     */
    static public function getUploadRoot()
    {
         return RW_Upload::getUploadRoot();
    }

    /**
     * Retorna a pasta raiz do public de todos os uploads
     *
     * @deprecated Usar RW_Upload
     *
     * @return string
     */
    static public function getUrlRoot()
    {
        return RW_Upload::getAssetsReservedRoot();
    }

}