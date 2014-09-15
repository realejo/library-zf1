# ZF1 Library

Biblioteca com models comuns utilizados nos projetos ZF1

## RW_App_Model_Base

Model para utilizar o Zend_Db_Table com funções mais comuns: fetchAll, fetchRow, getHtmlSelect, etc.

Inclui cache e paginator no fetchAll

Permite criar o campo '''deleted''' onde o registro é marcado como removido e não definitavemente removido da tabela no banco do dados.

## RW_App_Model_Db

Extende RW_App_Model_Base inlcuindo as funções insert, update, delete


## Instalação

A instalação pode ser feita pelo composer usando

```json
{
    "require": {
        "realejo/libaray-zf1" : "1.*"
    }
}
```

O autoloader do projeto em ZF1 deve estar usando o composer

