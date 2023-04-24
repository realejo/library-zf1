<?php

declare(strict_types=1);

/**
 * Model com acesso ao BD, Cache e Paginator padronizado.
 * Também permite que tenha acesso ao Loader
 *
 * Quando usar chaves multiplas deve sempre ser informado como array
 * Ex: array(key1=>val1, $key2=>$val2);
 */
class RW_App_Model_Base
{
    public const KEY_STRING = 'STRING';
    public const KEY_INTEGER = 'INTEGER';

    private RW_App_Loader $_loader;

    /**
     * Não pode ser usado dentro do Loader pois cada classe tem configurações diferentes
     */
    private ?RW_App_Model_Paginator $_paginator = null;

    /**
     * Não pode ser usado dentro do Loader pois cada classe tem configurações diferentes
     */
    private RW_App_Model_Cache $_cache;

    /**
     * Não pode ser usado dentro do Loader pois cada classe tem configurações diferentes
     */
    private ?RW_App_Model_Upload $_upload = null;

    /**
     * Define se deve usar o cache ou não
     */
    protected bool $useCache = false;

    /**
     * Define de deve usar o paginator
     */
    private bool $usePaginator = false;

    /**
     * Define a tabela a ser usada
     */
    protected string $table;

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
     */
    protected bool $useDeleted = false;

    /**
     * Define se deve mostrar os registros marcados como removido
     */
    protected bool $showDeleted = false;

    /**
     * Campo a ser usado no <option>
     */
    protected string $htmlSelectOption = '{nome}';

    /**
     * Campos a serem adicionados no <option> como data
     *
     * @var string|array
     */
    protected $htmlSelectOptionData;

    /**
     *
     * @param string|null $table Nome da tabela a ser usada
     * @param string|array $key Nome ou array de chaves a serem usadas
     *
     */
    public function __construct(string $table = null, $key = null)
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
        $this->key = $key;
        $this->table = $table;
    }

    public function getLoader(): RW_App_Loader
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

    public function getTableGateway(string $table = null): Zend_Db_Table
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
     * @param string|array $where OPTIONAL An SQL WHERE clause
     * @param string|array $order OPTIONAL An SQL ORDER clause.
     * @param int|null $count OPTIONAL An SQL LIMIT count.
     * @param int|null $offset OPTIONAL An SQL LIMIT offset.
     *
     * @return Zend_Db_Table_Select
     */
    public function getSelect($where = null, $order = null, int $count = null, int $offset = null): Zend_Db_Table_Select
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
                $where = ['deleted' => 0];
            }
        }

        // Veriifca se é um array para fazer o processamento abaixo
        if (!is_array($where)) {
            $where = (empty($where)) ? [] : [$where];
        }

        // Checks $where is deleted
        if ($this->getUseDeleted() && !$this->getShowDeleted() && !isset($where['deleted'])) {
            $where['deleted'] = 0;
        }

        // Verifica as clausulas especiais se houver
        $where = $this->getWhere($where);

        // processa as clausulas
        foreach ($where as $id => $w) {
            // Zend_Db_Expr
            if ($w instanceof Zend_Db_Expr) {
                $select->where($w);
                // Valor numerico
            } elseif (!is_numeric($id) && is_numeric($w)) {
                if (strpos($id, '.') === false) {
                    $id = "{$this->table}.$id";
                }
                $select->where("$id = ?", $w, 'INTEGER');
                // Texto e Data
            } elseif (!is_numeric($id)) {
                if (strpos($id, '.') === false) {
                    $id = "{$this->table}.$id";
                }
                $select->where("$id = ?", $w, 'STRING');
            } else {
                throw new LogicException("Condição inválida '$w' em " . get_class($this) . '::getSelect()');
            }
        }

        return $select;
    }

    /**
     * Retorna o select a ser usado no fetchAll e fetchRow
     */
    public function getTableSelect(): Zend_Db_Table_Select
    {
        return $this->getTableGateway()
            ->select(Zend_Db_Table_Abstract::SELECT_WITH_FROM_PART)
            ->setIntegrityCheck(false);
    }

    /**
     * Processa as clausulas especiais do where
     */
    public function getWhere(array $where): array
    {
        return $where;
    }

    /**
     * Retorna o SQL que será usado para a consulta
     *
     * @param string|array $where OPTIONAL An SQL WHERE clause
     * @param string|array $order OPTIONAL An SQL ORDER clause.
     * @param int|null $count OPTIONAL An SQL LIMIT count.
     * @param int|null $offset OPTIONAL An SQL LIMIT offset.
     *
     * @return string
     */
    public function getSQLString($where = null, $order = null, int $count = null, int $offset = null): string
    {
        return $this->getSelect($where, $order, $count, $offset)->assemble();
    }

    /**
     * Inclui campos extras ao retorna do fetchAll quando não estiver usando a paginação
     */
    protected function getFetchAllExtraFields(array $fetchAll): array
    {
        return $fetchAll;
    }

    /**
     * Retorna vários registros
     *
     * @param string|array $where OPTIONAL An SQL WHERE clause
     * @param string|array $order OPTIONAL An SQL ORDER clause.
     * @param int|null $count OPTIONAL An SQL LIMIT count.
     * @param int|null $offset OPTIONAL An SQL LIMIT offset.
     *
     * @return array|Zend_Paginator|null Lista de registros ou nulo se não localizar nenhum
     */
    public function fetchAll($where = null, $order = null, int $count = null, int $offset = null)
    {
        // Cria a assinatura da consulta
        if ($where instanceof Zend_Db_Select) {
            $md5 = md5($where->assemble());
        } else {
            $md5 = md5(
                var_export($this->showDeleted, true) . var_export($this->usePaginator, true) . var_export(
                    $where,
                    true
                ) . var_export($order, true) . var_export($count, true) . var_export($offset, true)
            );
        }

        // Verifica se tem no cache
        // o Zend_Paginator precisa do Zend_Paginator_Adapter_DbSelect para acessar o cache
        if ($this->getUseCache() && !$this->getUsePaginator() && $this->getCache()->test($md5)) {
            return $this->getCache()->load($md5);
        }

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
            if (!is_null($fetchAll) && count($fetchAll) > 0) {
                // Passa o $fetch para array para poder incluir campos extras
                $fetchAll = $fetchAll->toArray();

                // Verifica se deve adiciopnar campos extras
                $fetchAll = $this->getFetchAllExtraFields($fetchAll);
            } else {
                $fetchAll = null;
            }
        }

        // Grava a consulta no cache
        if ($this->getUseCache()) {
            $this->getCache()->save($fetchAll, $md5);
        }

        // Some garbage collection
        unset($select);

        // retorna o resultado da consulta
        return $fetchAll;
    }

    /**
     * Recupera um registro
     *
     * @param mixed $where condições para localizar o registro
     */
    public function fetchRow($where, $order = null): ?array
    {
        // Define se é a chave da tabela
        if (is_numeric($where) || is_string($where)) {
            // Veririfica se há chave definida
            if (empty($this->key)) {
                throw new InvalidArgumentException('Chave não definida em ' . get_class($this) . '::fetchRow()');
            }

            // Verifica se é uma chave muktipla ou com cast
            if (is_array($this->key)) {
                // Verifica se é uma chave simples com cast
                if (count($this->key) !== 1) {
                    // Não é possível acessar um registro com chave multipla usando apenas uma delas
                    throw new InvalidArgumentException(
                        'Não é possível acessar chaves múltiplas informando apenas uma em '
                        . get_class($this) . '::fetchRow()'
                    );
                }
                $where = [$this->getKey(true) => $where];
            } else {
                $where = [$this->key => $where];
            }
        }

        // Recupera o registro
        $fetchRow = $this->fetchAll($where, $order, 1);

        // Retorna o registro se algum foi encontrado
        return (!empty($fetchRow)) ? $fetchRow[0] : null;
    }


    /**
     * Retorna um array associado com a chave da tabela como chave do array
     *
     * Quando usar chaves multiplas será usada sempre a primeira
     *
     * @param string|array $where OPTIONAL An SQL WHERE clause
     * @param string|array $order OPTIONAL An SQL ORDER clause.
     * @param int|null $count OPTIONAL An SQL LIMIT count.
     * @param int|null $offset OPTIONAL An SQL LIMIT offset.
     *
     * @return array|null
     */
    public function fetchAssoc($where = null, $order = null, int $count = null, int $offset = null): ?array
    {
        // Recupera todos os registros
        $fetchAll = $this->fetchAll($where, $order, $count, $offset);

        // Veririca se foi localizado algum registro
        if (empty($fetchAll)) {
            return null;
        }

        // Associa pela chave da tabela
        $fetchAssoc = [];
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
     * @param string|array $where An SQL WHERE clause
     *
     * @return int
     * @todo se usar consulta com mais de uma tabela talvez de erro
     *
     */
    public function fetchCount($where = null): int
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
     * @param string $nome Name/ID a ser usado no <select>
     * @param string|null $selecionado Valor pré seleiconado
     * @param array $opts Opções adicionais
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
    public function getHtmlSelect(string $nome, string $selecionado = null, array $opts = []): string
    {
        // Recupera os registros
        $where = $opts['where'] ?? null;
        $fetchAll = $this->fetchAll($where);

        // Verifica o select_option_data
        if (isset($this->htmlSelectOptionData) && is_string($this->htmlSelectOptionData)) {
            $this->htmlSelectOptionData = [$this->htmlSelectOptionData];
        }

        // Verifica se deve mostrar a primeira opção em branco
        $showEmpty = (isset($opts['show-empty']) && $opts['show-empty'] === true);
        $neverShowEmpty = (isset($opts['show-empty']) && $opts['show-empty'] === false);

        // Define ao placeholder a ser usado
        $placeholder = $selectPlaceholder = $opts['placeholder'] ?? '';
        if (!empty($placeholder)) {
            $selectPlaceholder = "placeholder=\"$selectPlaceholder\"";
        }

        $grouped = $opts['grouped'] ?? false;

        // Define a chave a ser usada
        if (!empty($opts['key']) && is_string($opts['key'])) {
            $key = $opts['key'];
        } else {
            $key = $this->getKey(true);
        }

        // Monta as opções
        $options = '';
        $group = false;
        if (!empty($fetchAll)) {
            foreach ($fetchAll as $row) {
                preg_match_all('/\{([a-z_]*)\}/', $this->htmlSelectOption, $matches);

                // Troca pelos valores
                foreach ($matches[1] as $i => $m) {
                    $matches[1][$i] = $row[$m] ?? '';
                }

                // Define o option
                $option = str_replace($matches[0], $matches[1], $this->htmlSelectOption);

                // Verifica se deve adicionar campos ao data
                $data = '';
                if (isset($this->htmlSelectOptionData)) {
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
        if (!is_null($selecionado)) {
            $temp = str_replace(
                "<option value=\"$selecionado\"",
                "<option value=\"$selecionado\" selected=\"selected\"",
                $options
            );
            if ($temp === $options) {
                $selecionado = null;
            }
            $options = $temp;
        }

        // Abre o select
        $select = "<select class=\"form-control\" name=\"$nome\" id=\"$nome\" $selectPlaceholder>";

        // Verifica se tem valor padrão selecionado
        if ((empty($selecionado) || $showEmpty) && !$neverShowEmpty) {
            $select .= "<option value=\"\">$placeholder</option>";
        }

        // Coloca as opções
        $select .= $options;

        // Fecha o select
        $select .= '</select>';

        // Retorna o select
        return $select;
    }

    /**
     * Retorna o frontend para gravar o cache
     */
    public function getUpload(): ?RW_App_Model_Upload
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
     * @param bool $useCache
     */
    public function setUseCache($useCache)
    {
        // Grava o cache
        $this->useCache = $useCache;

        return $this;
    }

    public function getUseCache(): bool
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
     */
    public function getPaginator(): ?RW_App_Model_Paginator
    {
        if (!isset($this->_paginator)) {
            $this->_paginator = new RW_App_Model_Paginator();
        }

        $this->usePaginator = true;

        return $this->_paginator;
    }

    /**
     * Define se deve usar o paginator
     */
    public function setUsePaginator(bool $usePaginator): RW_App_Model_Base
    {
        // Grava o paginator
        $this->usePaginator = $usePaginator;

        return $this;
    }

    public function getUsePaginator(): bool
    {
        return $this->usePaginator;
    }

    /**
     * Getters and setters
     */

    public function getTable(): ?string
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
            foreach ($key as $type => $keyName) {
                $key = $keyName;
                break;
            }
        }

        return $key;
    }

    /**
     * @param string|array $key
     *
     * @return self
     */
    public function setKey($key): RW_App_Model_Base
    {
        if (empty($key) && !is_string($key) && !is_array($key)) {
            throw new InvalidArgumentException('Chave inválida em ' . get_class($this) . '::setKey()');
        }

        $this->key = $key;

        return $this;
    }

    /**
     * @return string|array
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function getHtmlSelectOption(): string
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
     * @param string|array|Zend_Db_Expr $order
     */
    public function setOrder($order): RW_App_Model_Base
    {
        if (empty($order) && !is_string($order) && !is_array($order) && (!$order instanceof Zend_Db_Expr)) {
            throw new InvalidArgumentException('Chave inválida em ' . get_class($this) . '::setOrder()');
        }

        $this->order = $order;

        return $this;
    }

    public function setHtmlSelectOption(string $htmlSelectOption): RW_App_Model_Base
    {
        $this->htmlSelectOption = $htmlSelectOption;
        return $this;
    }

    /**
     * @param array|string $htmlSelectOptionData
     *
     * @return self
     */
    public function setHtmlSelectOptionData($htmlSelectOptionData): RW_App_Model_Base
    {
        $this->htmlSelectOptionData = $htmlSelectOptionData;
        return $this;
    }

    /**
     * Retorna se irá usar o campo deleted ou remover o registro quando usar delete()
     */
    public function getUseDeleted(): bool
    {
        return $this->useDeleted;
    }

    /**
     * Define se irá usar o campo deleted ou remover o registro quando usar delete()
     */
    public function setUseDeleted(bool $useDeleted): RW_App_Model_Base
    {
        $this->useDeleted = $useDeleted;

        return $this;
    }

    /**
     * Retorna se deve retornar os registros marcados como removidos
     */
    public function getShowDeleted(): bool
    {
        return $this->showDeleted;
    }

    /**
     * Define se deve retornar os registros marcados como removidos
     */
    public function setShowDeleted(bool $showDeleted): RW_App_Model_Base
    {
        $this->showDeleted = $showDeleted;

        return $this;
    }
}