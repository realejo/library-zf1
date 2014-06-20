<?php
/**
 * Boostrap da Extranet BFFC
 *
 * @category   Bootstrap
 * @author     Realejo
 * @version    $Id: Bootstrap.php 1020 2014-01-13 14:02:07Z igor $
 * @copyright  Copyright (c) 2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Inicializa a conexão com o banco de dados
     * Usa o Zend_Cache para o metadata das tabelas
     *
     * @uses Zend_Cache
     */
    protected function _initDB()
    {
        // Configura a conexão com o BD
        $config = new Zend_Config($this->getOptions());
        $db = Zend_Db::factory($config->resources->db);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);

        // Configura o cache do metadata das tabelas
//         $frontendOptions = array( 'automatic_serialization' => true, 'lifetime'=>null );
//         $backendOptions  = array( 'cache_dir' => APPLICATION_PATH . '/../data/cache/db' );
//         $cache = Zend_Cache::factory('Core',
//                                     'File',
//                                     $frontendOptions,
//                                     $backendOptions);
//         Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
    }
}
