<?php

declare(strict_types=1);

/**
 * Gerenciador de tabelas e model carregados para evitar que sejam carregados na memória mais de uma vez
 */
class RW_App_Loader
{
    private ?array $_models = null;

    private ?array $_tables = null;

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
     * @return RW_App_Loader
     */
    public function setModel($class, $object)
    {
        // Verifica se existe loader aplicado a classe
        if (method_exists( $object , 'setLoader' )) {
            $object->setLoader($this);
        }

        $this->_models[$class] = $object;

        // Retorna o loader
        return $this;
    }

    /**
     * Retorna se a classe já está carregada
     *
     * @param string $class
     *
     * @return boolean
     */
    public function hasModel($class)
    {
        return isset($this->_models[$class]);
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
            throw new Exception("Tabela não definida em RW_App_Loader::getTable()");
        }

        // Verifica se a tabela já foi previamente carregada
        if (!isset($this->_tables[$table])) {
            $this->_tables[$table] = new Zend_Db_Table($table);
        }

        // Retorna a tabela
        return $this->_tables[$table];
    }
}