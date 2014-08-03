<?php
/**
 * Recupera as configurações do application
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */
class RW_Config
{
    /**
     * Cria um dump das tabelas do banco de dados.
     *
     * @param string $section OPCIONAL ENV a ser usando, se não inforamdop será usado APPLICATION_ENV
     * @throws Exception
     */
    static public function getApplicationIni($section = null)
    {
        // Verifica se uma das opções foi localizada
        if ( !defined('APPLICATION_PATH') || realpath(APPLICATION_PATH) === false ) {
            throw new Exception("constante APPLICATION_PATH não está definida em RW_Config::getApplicationIni()");
        }

        // Opções de localização do application.ini
        $configs = array(
                    APPLICATION_PATH . "/../configs/application.ini",
                    APPLICATION_PATH . "/configs/application.ini"
                  );

        // Verifica se a constante da marca (BFFC) esta definida
        if (defined('MARCA')) {
            if (is_numeric(MARCA)) {
                $configs[] = APPLICATION_PATH . "/../configs/application.".BFFC_Marca::getCssClass(MARCA).".ini";
                $configs[] = APPLICATION_PATH . "/configs/application.".BFFC_Marca::getCssClass(MARCA).".ini";
            } else {
                $configs[] = APPLICATION_PATH . "/../configs/application.".MARCA.".ini";
                $configs[] = APPLICATION_PATH . "/configs/application.".MARCA.".ini";
            }
        }

        // Carrega as configurações do config
        $configpath = false;
        foreach($configs as $c) {
            if ( file_exists($c) ) {
                $configpath = $c;
            }
        }

        // Verifica se uma das opções foi localizada
        if ( $configpath === false ) {
            $marca = (defined('MARCA')) ? '(marca='.BFFC_Marca::getCssClass(MARCA) .')': '' ;
            throw new Exception("Nenhum arquivo de configuração application.ini encontrado do diretório '/configs' $marca em RW_Config::getApplicationIni()");
        }

        // Verifica o ambiente
        $section = (empty($section)) ? APPLICATION_ENV : $section;

        // Instância o arquivo aplication.ini
        return new Zend_Config_Ini($configpath, $section);
    }
}
