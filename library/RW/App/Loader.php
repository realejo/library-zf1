<?php
/**
 * Gerenciador de tabelas e model carregados para evitar que sejam carregados na memória mais de uma vez
 *
 * @author     Realejo
 * @version    $Id: Loader.php 313 2014-04-14 18:31:13Z rodrigo $
 * @copyright  Copyright (c) 2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */
class App_Loader
{
    /**
     * @var array
     */
    private $_models;

    /**
     * @var array
     */
    private $_tables;

    /**
     * @param string $model
     *
     * @return $model
     */
    public function getModel($model)
    {
        // Verifica se o model já foi previamente carregado
        if (!$this->hasModel($model)) {
            $this->_models[$model] = new $model();

            // Verifica se existe loader aplicado a classe
            if (method_exists( $this->_models[$model] , 'setLoader' )) {
                $this->_models[$model]->setLoader($this);
            }
        }

        // Retorna o model
        return $this->_models[$model];
    }

    /**
     * Grava uma classe dentro do loader
     *
     * @param string $class
     * @param mixed $object
     *
     * @return \Realejo\App\Loader\Loader
     */
    public function setModel($class, $object)
    {
        $this->_classes[$class] = new $class();

        // Verifica se existe loader aplicado a classe
        if (method_exists( $this->_classes[$class] , 'setLoader' )) {
            $this->_classes[$class]->setLoader($this);
        }

        // Retorna o loader
        return $this;
    }

    /**
     * Retorna se a classe já está carregada
     *
     * @param string $class
     *
     * @return mixed
     */
    public function hasModel($class)
    {
        return isset($this->_classes[$class]);
    }

    /**
     * @param string $table
     *
     * @return Zend_Db_Table
     */
    public function getTable($table)
    {
        // Verifica se existe uma tabela definida
        if (empty($table)) {
            throw new Exception("Tabela não definida em App_Loader::getTable()");
        }

        // Verifica se a tabela já foi previamente carregada
        if (!isset($this->_tables[$table])) {
            $this->_tables[$table] = new Zend_Db_Table($table);
        }

        // Retorna a tabela
        return $this->_tables[$table];
    }
}