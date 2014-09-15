<?php
/**
 * Boostrap exdemplo para um applicativo que use relaejo/library-zf1
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
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
    }
}
