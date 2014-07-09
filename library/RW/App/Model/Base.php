<?php
/**
 * Model com acesso ao BD, Cache e Paginator padronizado.
 * Também permite que tenha acesso ao Loader
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_App_Model_Base
{
    /**
     * @var RW_App_Loader
     */
    private $_loader;

    /**
     * Não pode ser usado dentro do Loader pois cada classe tem configurações diferentes
     * @var RW_App_Model_Paginator
     */
    private $_paginator;

    /**
     * Não pode ser usado dentro do Loader pois cada classe tem configurações diferentes
     * @var RW_App_Model_Cache
     */
    private $_cache;

    /**
     * Não pode ser usado dentro do Loader pois cada classe tem configurações diferentes
     * @var RW_App_Model_Upload
     */
    private $_upload;

    /**
     * Define se deve usar o cache ou não
     * @var boolean
     */
    protected $useCache = false;

    /**
     * Define de deve usar o paginator
     * @var boolean
     */
    private $usePaginator = false;

    /**
     * Define a tabela a ser usada
     * @var string
     */
    protected $table;

    /**
     * Define o nome da chave
     * @var string
     */
    protected $key;

    /**
     * Define a ordem padrão a ser usada na consultas
     *
     * @var string
     */
    protected $order;

    /**
     * Define se deve remover os registros ou apenas marcar como removido
     *
     * @var boolean
     */
    protected $useDeleted = false;

    /**
     * Define se deve mostrar os registros marcados como removido
     *
     * @var boolean
     */
    protected $showDeleted = false;

    /**
     * Campo a ser usado no <option>
     *
     * @var string
     */
    protected $htmlSelectOption = '{nome}';

    /**
     * Campos a serem adicionados no <option> como data
     *
     * @var string|array
     */
    protected $htmlSelectOptionData;

    public function __construct($table = null, $key = null, $dbAdapter = null)
    {
        // Verifica o nome da tabela
        if (empty($table) && !is_string($table)) {
            if (isset($this->table)) {
                $table = $this->table;
            } else {
                throw new \Exception('Nome da tabela inválido');
            }
        }

        // Verifica o nome da chave
        if (empty($key) && !is_string($key)) {
            if (isset($this->key)) {
                $key = $this->key;
            } else {
                throw new Exception('Nome da chave inválido');
            }
        }

        // Define a chave e o nome da tabela
        $this->key = $key;
        $this->table = $table;

        // Define o adapter padrão
        if ( !empty($dbAdapter) ) {
            if ($dbAdapter instanceof Zend_Db_Adapter_Abstract) {
                $this->_dbAdapter = $dbAdapter;
            } else {
                throw new Exception('Adapter deve ser Zend\Db\Adapter\AdapterInterface');
            }
        }
    }

    /**
     * @return RW_App_Loader
     */
    public function getLoader()
    {
        if (!isset($this->_loader)) {
            $this->setLoader(new RW_App_Loader());
        }

        return $this->_loader;
    }

    public function setLoader($loader)
    {
        $this->_loader = $loader;
    }

    /**
     * @param string $table
     *
     * @return Zend_Db_Table
     */
    public function getTableGateway($table = null)
    {
        if (empty($table) && isset($this->table)) {
            $table = $this->table;
        }

        if (empty($table)) {
            throw new Exception('Tabela não definida em ' . get_class($this) . '::getTable()');
        }

        // retorna o tabela
        return $this->getLoader()->getTable($table);
    }

    /**
     * Retorna o select para a consulta
     *
     * @param string|array $where  OPTIONAL An SQL WHERE clause
     * @param string|array $order  OPTIONAL An SQL ORDER clause.
     * @param int          $count  OPTIONAL An SQL LIMIT count.
     * @param int          $offset OPTIONAL An SQL LIMIT offset.
     *
     * @return Zend_Db_Table_Select
     */
    public function getSelect($where = null, $order = null, $count = null, $offset = null)
    {
        // Retorna o select para a tabela
        $select = $this->getTableSelect();

        // Verifica se existe ordem padrão
        if (empty($order) && isset($this->order)) {
            if (is_string($this->order) && strpos($this->order, '=') !== false) {
                $this->order = new Zend_Db_Expr($this->order);
            }
            $order = $this->order;
        }

        // Define a ordem
        $select->order($order);

        // Verifica se há paginação, não confundir com o Zend_Paginator
        if (!is_null($count)) {
            $select->limit($count, $offset);
        }

        // Checks $where is not null
        if (empty($where)) {
            if ($this->getUseDeleted() && !$this->getShowDeleted()) {
                $where = array('deleted' => 0);
            }
        }

        // Veriifca se é um array para fazer o processamento abaixo
        if (!is_array($where)) {
            $where = (empty($where)) ? array() : array($where);
        }

        // Checks $where is deleted
        if ($this->getUseDeleted() && !$this->getShowDeleted() && !isset($where['deleted'])) {
            $where['deleted'] = 0;
        }

        // Verifica as clausulas especiais se houver
        $where = $this->getWhere($where);

        // processa as clausulas
        foreach($where as $id=>$w) {
            // Zend_Db_Expr
            if ($w instanceof Zend_Db_Expr) {
                $select->where($w);

            // Valor numerico
            } elseif (!is_numeric($id) && is_numeric($w)) {
                if (strpos($id,'.') === false) $id = "{$this->table}.$id";
                $select->where("$id = ?", $w, 'INTEGER');

            // Texto e Data
            } elseif (!is_numeric($id)) {
                if (strpos($id,'.') === false) $id = "{$this->table}.$id";
                $select->where("$id = ?", $w, 'STRING');

            } else {
                var_dump($where, '$where');
                var_dump($w, '$$w');
                throw new Exception("Condição inválida '$w' em " . get_class($this) . '::getSelect()');
            }
        }

        return $select;
    }

    /**
     * Retorna o select a ser usado no fetchAll e fetchRow
     *
     * @return Zend_Db_Table_Select
     */
    public function getTableSelect()
    {
        return $this->getTableGateway()->select();
    }

    /**
     * Processa as cluasulas especiais do where
     *
     * @param array $where
     *
     * @return array
     */
    public function getWhere($where)
    {
        return $where;
    }

    /**
     * Retorna o SQL que será usado para a consulta
     *
     * @param string|array $where  OPTIONAL An SQL WHERE clause
     * @param string|array $order  OPTIONAL An SQL ORDER clause.
     * @param int          $count  OPTIONAL An SQL LIMIT count.
     * @param int          $offset OPTIONAL An SQL LIMIT offset.
     *
     * @return string
     */
    public function getSQLString($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->getSelect($where, $order, $count, $offset)->assemble();
    }

    /**
     * Inclui campos extras ao retorna do fetchAll quando não estiver usando a paginação
     *
     * @param array $fetchAll
     *
     * @return array
     */
    protected function getFetchAllExtraFields($fetchAll)
    {
        return $fetchAll;
    }

    /**
     * Retorna vários registros
     *
     * @param string|array $where  OPTIONAL An SQL WHERE clause
     * @param string|array $order  OPTIONAL An SQL ORDER clause.
     * @param int          $count  OPTIONAL An SQL LIMIT count.
     * @param int          $offset OPTIONAL An SQL LIMIT offset.
     *
     * @return array|null Lista de registros ou nulo se não localizar nenhum
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        // Cria a assinatura da consulta
        if ($where instanceof Zend_Db_Select) {
            $md5 = md5($where->assemble());
        } else {
            $md5 = md5(var_export($this->showDeleted, true) . var_export($this->usePaginator, true) . var_export($where, true) . var_export($order, true) . var_export($count, true) . var_export($offset, true));
        }

        // Verifica se tem no cache
        // o Zend_Paginator precisa do Zend_Paginator_Adapter_DbSelect para acessar o cache
        if ($this->getUseCache() && !$this->getUsePaginator() && $this->getCache()->test($md5)) {
            return $this->getCache()->load($md5);

        } else {

            // Define a consulta
            if ($where instanceof Zend_Db_Select) {
                $select = $where;
            } else {
                $select = $this->getSelect($where, $order, $count, $offset);
            }

            // Verifica se deve usar o Paginator
            if ($this->getUsePaginator()) {
                $fetchAll = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));

                // Verifica se deve usar o cache
                if ($this->getUseCache()) {
                    $fetchAll->setCacheEnabled(true)->setCache($this->getCache());
                }

                // Configura o paginator
                $fetchAll->setPageRange($this->getPaginator()->getPageRange());
                $fetchAll->setCurrentPageNumber($this->getPaginator()->getCurrentPageNumber());
                $fetchAll->setItemCountPerPage($this->getPaginator()->getItemCountPerPage());

            } else {
                // Recupera os registros do banco de dados
                $fetchAll = $this->getTableGateway()->fetchAll($select);

                // Verifica se foi localizado algum registro
                if ( !is_null($fetchAll) && count($fetchAll) > 0 ) {
                    // Passa o $fetch para array para poder incluir campos extras
                    $fetchAll = $fetchAll->toArray();

                    // Verifica se deve adiciopnar campos extras
                    $fetchAll = $this->getFetchAllExtraFields($fetchAll);
                } else {
                    $fetchAll = null;
                }

                // Grava a consulta no cache
                if ($this->getUseCache()) {
                    $this->getCache()->save($fetchAll, $md5);
                }
            }

            // Some garbage collection
            unset($select);

            // retorna o resultado da consulta
            return $fetchAll;
        }
    }

    /**
     * Recupera um registro
     *
     * @param mixed $where condições para localizar o registro
     *
     * @return array|null Array com o registro ou null se não localizar
     */
    public function fetchRow($where, $order = null)
    {
        // Define se é a chave da tabela
        if (is_numeric($where)) {
            if (empty($this->key)) {
                throw new Exception('Chave não definida em ' . get_class($this) . '::fetchRow()');
            } else{
                $where = array($this->key=>$where);
            }
        }

        // Recupera o registro
        $fetchRow = $this->fetchAll($where, $order, 1);

        // Retorna o registro se algum foi encontrado
        return (!empty($fetchRow))? $fetchRow[0] : null;
    }


    /**
     * Retorna um array associado com a chave da tabela como chave do array
     *
     * @param string|array $where  OPTIONAL An SQL WHERE clause
     * @param string|array $order  OPTIONAL An SQL ORDER clause.
     * @param int          $count  OPTIONAL An SQL LIMIT count.
     * @param int          $offset OPTIONAL An SQL LIMIT offset.
     *
     * @return array|null
     */
    public function fetchAssoc($where = null, $order = null, $count = null, $offset = null)
    {
        // Recupera todos os registros
        $fetchAll = $this->fetchAll($where, $order, $count, $offset);

        // Veririca se foi localizado algum registro
        if (empty($fetchAll)) {
            return null;
        }

        // Associa pela chave da tabela
        $fetchAssoc = array();
        foreach ($fetchAll as $row) {
            $fetchAssoc[$row[$this->key]] = $row;
        }

        // Some garbage collection
        unset($fetchAll);

        // Retorna o array reordenado
        return $fetchAssoc;
    }

    /**
     * Retorna o total de registros encontrados com a consulta
     *
     * @todo se usar consulta com mais de uma tabela talvez de erro
     *
     * @param string|array $where  An SQL WHERE clause
     *
     * @return int
     */
    public function fetchCount($where = null)
    {
        // Define o select
        $select = $this->getSelect($where);

        // Altera as colunas
        $select->reset('columns')->columns(new Zend_Db_Expr('count(*) as total'));

        $fetchRow = $this->fetchRow($select);

        if (empty($fetchRow)) {
            return 0;
        } else {
            return $fetchRow['total'];
        }
    }

    /**
     * Retorna o HTML de um <select> apra usar em formulários
     *
     * @param string $nome        Nome/ID a ser usado no <select>
     * @param string $selecionado Valor pré seleiconado
     * @param string $opts        Opções adicionais
     *
     * As opções adicionais podem ser
     *  - where       => filtro para ser usando no fetchAll()
     *  - placeholder => legenda quando nenhum estiver selecionado e/ou junto com show-empty
     *  - show-empty  => mostra um <option> vazio no inicio mesmo com um selecionado
     *
     * @return string
     */
    public function getHtmlSelect($nome, $selecionado = null, $opts = null)
    {
        // Recupera os registros
        $where = (isset($opts['where'])) ? $opts['where'] : null;
        $fetchAll = $this->fetchAll($where);

        // Verifica o select_option_data
        if (isset($this->htmlSelectOptionData) && is_string($this->htmlSelectOptionData)) {
            $this->htmlSelectOptionData = array(
                $this->htmlSelectOptionData
            );
        }

        // Verifica se deve mostrar a primeira opção em branco
        $showEmpty = (isset($opts['show-empty']) && $opts['show-empty'] === true);
        $neverShowEmpty = (isset($opts['show-empty']) && $opts['show-empty'] === false);

        // Define ao plcaeholder aser usado
        $placeholder = $selectPlaceholder = (isset($opts['placeholder'])) ? $opts['placeholder'] : '';
        if (!empty($placeholder)) {
            $selectPlaceholder = "placeholder=\"$selectPlaceholder\"";
        }

        // Monta as opções
        $options = '';
        if (! empty($fetchAll)) {
            foreach ($fetchAll as $row) {
                preg_match_all('/\{([a-z_]*)\}/', $this->htmlSelectOption, $matches);

                // Troca pelos valores
                foreach ($matches[1] as $i => $m) {
                    $matches[1][$i] = (isset($row[$m])) ? $row[$m] : '';
                }

                // Define o option
                $option = str_replace($matches[0], $matches[1], $this->htmlSelectOption);

                // Verifica se deve adicionar campos ao data
                $data = '';
                if (isset($this->htmlSelectOptionData)) {
                    $data = '';
                    foreach ($this->htmlSelectOptionData as $name => $field) {
                        if (is_numeric($name)) {
                            $name = $field;
                        }
                        $data .= " data-$name=\"{$row[$field]}\"";
                    }
                }
                $options .= "<option value=\"{$row[$this->key]}\" $data>$option</option>";
            }
        }

        // Verifica se tem valor padrão
        if (! is_null($selecionado)) {
            $temp = str_replace("<option value=\"$selecionado\"", "<option value=\"$selecionado\" selected=\"selected\"", $options);
            if ($temp === $options)
                $selecionado = null;
            $options = $temp;
        }

        // Abre o select
        $select = "<select class=\"form-control\" name=\"$nome\" id=\"$nome\" $selectPlaceholder>";

        // Verifica se tem valor padrão selecionado
        if ((empty($selecionado) || $showEmpty) && !$neverShowEmpty)
            $select .= "<option value=\"\">$placeholder</option>";

        // Coloca as opções
        $select .= $options;

        // Fecha o select
        $select .= '</select>';

        // Retorna o select
        return $select;
    }

    /**
     * Retorna o frontend para gravar o cache
     *
     * @return RW_App_Model_Upload
     */
    public function getUpload()
    {
        if (!isset($this->_upload)) {
            $this->_upload = new RW_App_Model_Upload();
        }
        return $this->_upload;
    }

    /**
     * Retorna o frontend para gravar o cache
     *
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        $cache = $this->getLoader()->getModel('RW_App_Model_Cache');
        return $cache->getFrontend(get_class($this));
    }

    /**
     * Define se deve usar o cache
     *
     * @param boolean $useCache
     */
    public function setUseCache($useCache)
    {
        // Grava o cache
        $this->useCache = $useCache;

        // Mantem a cadeia
        return $this;
    }

    /**
     * Retorna se deve usar o cache
     *
     * @return boolean
     */
    public function getUseCache()
    {
        return $this->useCache;
    }

    /**
     * PAGINATOR
     * Diferente do cache, se gravar qualquer variável do paginator ele será criado
     */

    /**
     * Retorna o frontend para gravar o cache
     *
     * @return RW_App_Model_Paginator
     */
    public function getPaginator()
    {
        if (!isset($this->_paginator)) {
            $this->_paginator = new RW_App_Model_Paginator();
        }

        $this->usePaginator = true;

        return $this->_paginator;
    }

    /**
     * Define se deve usar o paginator
     *
     * @param boolean $usepaginator
     */
    public function setUsePaginator($usePaginator)
    {
        // Grava o paginator
        $this->usePaginator = $usePaginator;

        // Mantem a cadeia
        return $this;
    }

    /**
     * Retorna se deve usar o paginator
     *
     * @return boolean
     */
    public function getUsePaginator()
    {
        return $this->usePaginator;
    }

    /**
     * Getters and setters
     */

    /**
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     *
     * @return string|array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     *
     * @return string
     */
    public function getHtmlSelectOption()
    {
        return $this->htmlSelectOption;
    }

    /**
     *
     * @return array|string
     */
    public function getHtmlSelectOptionData()
    {
        return $this->htmlSelectOptionData;
    }

    /**
     *
     * @param string $order
     *
     * @return self
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }


    /**
     *
     * @param string $htmlSelectOption
     *
     * @return self
     */
    public function setHtmlSelectOption($htmlSelectOption)
    {
        $this->htmlSelectOption = $htmlSelectOption;
        return $this;
    }

    /**
     *
     * @param array|string $htmlSelectOptionData
     *
     * @return self
     */
    public function setHtmlSelectOptionData($htmlSelectOptionData)
    {
        $this->htmlSelectOptionData = $htmlSelectOptionData;
        return $this;
    }

    /**
     * Retorna se irá usar o campo deleted ou remover o registro quando usar delete()
     *
     * @return boolean
     */
    public function getUseDeleted()
    {
        return $this->useDeleted;
    }

    /**
     * Define se irá usar o campo deleted ou remover o registro quando usar delete()
     *
     * @param boolean $useDeleted
     *
     * @return  self
     */
    public function setUseDeleted($useDeleted)
    {
        $this->useDeleted = $useDeleted;

        // Mantem a cadeia
        return $this;
    }

    /**
     * Retorna se deve retornar os registros marcados como removidos
     *
     * @return boolean
     */
    public function getShowDeleted()
    {
        return $this->showDeleted;
    }

    /**
     * Define se deve retornar os registros marcados como removidos
     *
     * @param boolean $showDeleted
     *
     * @return  self
     */
    public function setShowDeleted($showDeleted)
    {
        $this->showDeleted = $showDeleted;

        // Mantem a cadeia
        return $this;
    }
}