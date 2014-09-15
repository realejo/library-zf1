<?php
/**
 * Gerenciador de cache utilizado pelo App_Model
 *
 * Ele cria automaticamente a pasta de cache, dentro de data/cache, baseado no nome da classe
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
abstract class RW_App_Loader_Awareness
{
    /**
     * @var RW_App_Loader
     */
    private $_loader;

    /**
     * Retorna o App_Loader a ser usado
     *
     * @return RW_App_Loader
     */
    public function getLoader()
    {
        if (!isset($this->_loader)) {
            $this->setLoader(new RW_App_Loader());
        }

        return $this->_loader;
    }

    /**
     * Grava o App_Loader que deve ser usado
     * Ele é usado com DI durante a criação do model no App_Loader
     *
     * @param RW_App_Loader $loader
     */
    public function setLoader($loader)
    {
        $this->_loader = $loader;

        return $this->_loader;
    }
}
