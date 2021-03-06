<?php
/**
 * Model com acesso ao BD, Cache e Paginator padronizado.
 * Também permite que tenha acesso ao Loader
 *
 * Quando usar chaves multiplas deve sempre ser informado como array
 * Ex: array(key1=>val1, $key2=>$val2);
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_App_Model_Base
{
    const KEY_STRING  = 'STRING';
    const KEY_INTEGER = 'INTEGER';

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
     * @var string|array
     */
    protected $key;

    /**
     * Define a ordem padrão a ser usada na consultas
     *
     * @var string|array
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

    /**
     *
     * @param string       $table Nome da tabela a ser usada
     * @param string|array $key   Nome ou array de chaves a serem usadas
     *
     */
    public function __construct($table = null, $key = null)
    {
        // Verifica o nome da tabela
        if (empty($table) && !is_string($table)) {
            if (isset($this->table)) {
                $table = $this->table;
            } else {
                throw new InvalidArgumentException('Nome da tabela inválido em RW_App_Model_Base');
            }
        }

        // Verifica o nome da chave
        if (empty($key) && !is_string($key) && !is_array($key)) {
            if (isset($this->key)) {
                $key = $this->key;
            } else {
                throw new InvalidArgumentException('Chave inválida em RW_App_Model_Base');
            }
        }

        // Define a chave e o nome da tabela
        $this->key   = $key;
        $this->table = $table;
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
            throw new InvalidArgumentException('Tabela não definida em ' . get_class($this) . '::getTable()');
        }

        // retorna a tabela
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
                throw new LogicException("Condição inválida '$w' em " . get_class($this) . '::getSelect()');
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
        return $this->getTableGateway()
                    ->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                    ->setIntegrityCheck(false);
    }

    /**
     * Processa as clausulas especiais do where
     *
     * @param array|string $where
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
        if (is_numeric($where) || is_string($where)) {
            // Veririfica se há chave definida
            if (empty($this->key)) {
                throw new InvalidArgumentException('Chave não definida em ' . get_class($this) . '::fetchRow()');

            // Verifica se é uma chave muktipla ou com cast
            } elseif (is_array($this->key)) {

                // Verifica se é uma chave simples com cast
                if (count($this->key) == 1) {
                    $where = array($this->getKey(true)=>$where);

                // Não é possível acessar um registro com chave multipla usando apenas uma delas
                } else {
                    throw new InvalidArgumentException('Não é possível acessar chaves múltiplas informando apenas uma em ' . get_class($this) . '::fetchRow()');
                }

            } else {
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
     * Quando usar chaves multiplas será usada sempre a primeira
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
        $key = $this->getKey(true);
        foreach ($fetchAll as $row) {
            $fetchAssoc[$row[$key]] = $row;
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
     * @param string $nome        Name/ID a ser usado no <select>
     * @param string $selecionado Valor pré seleiconado
     * @param string $opts        Opções adicionais
     *
     * Os valores de option serão os valores dos campos definidos em $htmlSelectOption
     * Aos options serão adicionados data-* de acordo com os campos definidos em $htmlSelectOptionData
     *
     * Quando usar chaves multiplas será usada sempre a primeira, a menos que use o parametro 'key' abaixo
     *
     * As opções adicionais podem ser
     *  - where       => filtro para ser usando no fetchAll()
     *  - placeholder => legenda quando nenhum estiver selecionado e/ou junto com show-empty
     *                   se usdo com FALSE, nunca irá mostrar o vazio, mesmo que não tenha um selecionado
     *  - show-empty  => mostra um <option> vazio no inicio mesmo com um selecionado
     *  - grouped     => mostra o <optgroup> usando com label e agregador o campo informado
     *  - key         => campo a ser usado como chave, se não informado será usado a chave definida
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

        // Define ao placeholder a ser usado
        $placeholder = $selectPlaceholder = (isset($opts['placeholder'])) ? $opts['placeholder'] : '';
        if (!empty($placeholder)) {
            $selectPlaceholder = "placeholder=\"$selectPlaceholder\"";
        }

        $grouped = (isset($opts['grouped'])) ? $opts['grouped'] : false;

        // Define a chave a ser usada
        if (isset($opts['key']) && !empty($opts['key']) && is_string($opts['key'])) {
            $key = $opts['key'];
        } else {
            $key = $this->getKey(true);
        }

        // Monta as opções
        $options = '';
        $group = false;
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

                // Verifica se deve usar optgroup e cria o label
                if ($grouped !== false) {
                    if ($group !== $row[$grouped]) {
                        if ($group !== false) {
                            $options .= '</optgroup>';
                        }
                        $options .= '<optgroup label="' . $row[$grouped] . '">';
                        $group = $row[$grouped];
                    }
                }

                $options .= "<option value=\"{$row[$key]}\" $data>$option</option>";
            }

            // Fecha o último grupo se ele existir
            if ($grouped !== false && $group !== false) {
                $options .= '</optgroup>';
            }
        }

        // Verifica se tem valor padrão
        if ( !is_null($selecionado) ) {
            $temp = str_replace("<option value=\"$selecionado\"", "<option value=\"$selecionado\" selected=\"selected\"", $options);
            if ($temp === $options) {
                $selecionado = null;
            }
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
     * não pode usar o loader pois irá afetar a paginação quando houve mais de uma sendo usada
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
     * Retorna a chave definida para a tabela
     *
     * @param $returnSingle OPCIONAL Quando for uma chave multipla, use TRUE para retorna a primeira chave
     *
     * @return string|array
     */
    public function getKey($returnSingle = false)
    {
        $key = $this->key;

        // Verifica se é para retorna apenas a primeira da chave multipla
        if (is_array($key) && $returnSingle === true) {
            if (is_array($key)) {
                foreach($key as $type=>$keyName) {
                    $key = $keyName;
                    break;
                }
            }
        }

        return $key;
    }

    /**
     * @param string|array
     *
     * @return self
     */
    public function setKey($key)
    {
        if (empty($key) && !is_string($key) && !is_array($key)) {
            throw new InvalidArgumentException('Chave inválida em ' . get_class($this) . '::setKey()');
        }

        $this->key = $key;

        return $this;
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
     * @param string|array|Zend_Db_Expr $order
     *
     * @return self
     */
    public function setOrder($order)
    {
        if (empty($order) && !is_string($order) && !is_array($order) && ( ! $order instanceof Zend_Db_Expr)) {
            throw new InvalidArgumentException('Chave inválida em ' . get_class($this) . '::setOrder()');
        }

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