<?php
/**
 * Model basico para o RW_App_Model_Base com as funções de insert, update e delete
 *
 * Quando usar chaves multiplas deve sempre ser informado como array
 * Ex: array(key1=>val1, $key2=>$val2);
 *
 * Sempre é preciso informar todas as chaves. Caso queira usar apenas algumas
 * use useAllKeys = false
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_App_Model_Db extends RW_App_Model_Base
{

    private $_lastInsertSet;

    private $_lastInsertKey;

    private $_lastUpdateSet;

    private $_lastUpdateDiff;

    private $_lastUpdateKey;

    private $_lastDeleteKey;

    /**
     * Grava um novo registro
     *
     * @param array $dados Dados a serem cadastrados
     *
     * @return int boolean
     */
    public function insert($set)
    {

        // Verifica se há algo a ser adicionado
        if (empty($set)) {
            return false;
        }

        // Remove os campos vazios
        foreach ($set as $field => $value) {
            if (is_string($value)) {
                $set[$field] = trim($value);
                if ($set[$field] === '') {
                    $set[$field] = null;
                }
            }
        }

        // Grava o ultimo set incluído para referencia
        $this->_lastInsertSet = $set;

        // Grava o set no BD
        $key = $this->getTableGateway()->insert($set);

        // Grava a chave criada para referencia
        $this->_lastInsertKey = $key;

        // Limpa o cache se necessário
        if ($this->getUseCache()) {
            $this->getCache()->clean();
        }

        // Retorna o código do registro recem criado
        return $key;
    }

    /**
     * Altera um registro
     *
     * @param array $set Dados a serem atualizados
     * @param int   $key Chave do registro a ser alterado
     *
     * @return boolean
     */
    public function update($set, $key)
    {
        // Verifica se o código é válido
        if ( !is_numeric($key) ) {
            throw new \Exception("O código <b>'$key'</b> inválido em " . get_class($this) . "::update()");
        }

        // Verifica se há algo para alterar
        if (empty($set)) {
            return false;
        }

        // Recupera os dados existentes
        $row = $this->fetchRow($key);

        // Verifica se existe o registro
        if (empty($row)) {
            return false;
        }

        // Remove os campos vazios
        foreach ($set as $field => $value) {
            if (is_string($value)) {
                $set[$field] = trim($value);
                if ($set[$field] === '') {
                    $set[$field] = null;
                }
            }
        }

        // Verifica se há o que atualizar
        $diff = array_diff_assoc($set, $row);

        // Grava os dados alterados para referencia
        $this->_lastUpdateSet  = $set;
        $this->_lastUpdateKey  = $key;

        // Grava o que foi alterado
        $this->_lastUpdateDiff = array();
        foreach ($diff as $field=>$value) {
            $this->_lastUpdateDiff[$field] = array($row[$field], $value);
        }

        // Verifica se há algo para atualizar
        if (empty($diff)) {
            return false;
        }

        // Salva os dados alterados
        $return = $this->getTableGateway()->update($diff, "{$this->key}=$key");

        // Limpa o cache, se necessário
        if ($this->getUseCache()) {
            $this->getCache()->clean();
        }

        // Retorna que o registro foi alterado
        return $return;
    }

    /**
     * Excluí um registro
     *
     * @param int $cda Código da registro a ser excluído
     *
     * @return bool Informa se teve o regsitro foi removido
     */
    public function delete($key)
    {
        if (! is_numeric($key) || empty($key)) {
            throw new \Exception("O código <b>'$key'</b> inválido em " . get_class($this) . "::delete()");
        }

        // Grava os dados alterados para referencia
        $this->_lastDeleteKey = $key;

        // Verifica se deve marcar como removido ou remover o registro
        if ($this->useDeleted === true) {
            $return = $this->getTableGateway()->update(array('deleted' => 1), "{$this->key}=$key");
        } else {
            $return = $this->getTableGateway()->delete("{$this->key}=$key");
        }

        // Limpa o cache se necessario
        if ($this->getUseCache()) {
            $this->getCache()->clean();
        }

        // Retorna se o registro foi excluído
        return $return;
    }

    public function save($dados)
    {
        if (! isset($dados[$this->id])) {

            return $this->insert($dados);
        } else {
            // Caso não seja, envia um Exception
            if (! is_numeric($dados[$this->id])) {
                throw new \Exception("Inválido o Código '{$dados[$this->id]}' em '{$this->table}'::save()");
            }

            if ($this->fetchRow($dados[$this->id])) {
                return $this->update($dados, $dados[$this->id]);
            } else {
                throw new \Exception("{$this->id} key does not exist");
            }
        }
    }

    /**
     * Retorna a chave no formato que ela deve ser usada
     *
     * @param Zend_Db_Expr|string|array $key
     *
     * @return Zend_Db_Expr|string
     */
    private function _getKeyWhere($key)
    {
        if ($key instanceof Zend_Db_Expr) {
            return $key;

        } elseif (is_string($this->key) && is_numeric($key)) {
            return "{$this->key} = $key";

        } elseif (is_array($this->key)) {

        } else {
            throw new LogicException("Chave mal definida em ' . get_class($this) . '::_getWhere");
        }
    }

    /**
     *
     * @return array
     */
    public function getLastInsertSet()
    {
        return $this->_lastInsertSet;
    }

    /**
     *
     * @return int
     */
    public function getLastInsertKey()
    {
        return $this->_lastInsertKey;
    }

    /**
     *
     * @return array
     */
    public function getLastUpdateSet()
    {
        return $this->_lastUpdateSet;
    }

    /**
     *
     * @return array
     */
    public function getLastUpdateDiff()
    {
        return $this->_lastUpdateDiff;
    }

    /**
     *
     * @return int
     */
    public function getLastUpdateKey()
    {
        return $this->_lastUpdateKey;
    }

    /**
     *
     * @return int
     */
    public function getLastDeleteKey()
    {
        return $this->_lastDeleteKey;
    }
}
