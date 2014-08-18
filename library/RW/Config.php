<?php
/**
 * Recupera as configurações contidas em um arquivo .ini
 * Considera que o arquivo está em um pasta configs no mesmo nivel que a
 * pasta do aplicativo ou dentro do pasta do aplicativo
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
     * @param string $file_prefix Perfixo do arquivo a ser usado (SEM .INI) 
     * @param string $section OPCIONAL section a ser usanda, se não informada será usado o que está definido em APPLICATION_ENV
     * 
     * @return Zend_Config_Ini
     */
    static public function getIni($file_prefix, $section = null)
    {
        // Verifica se uma das opções foi localizada
        if ( !defined('APPLICATION_PATH') || realpath(APPLICATION_PATH) === false ) {
            throw new Exception("constante APPLICATION_PATH não está definida em RW_Config::getApplicationIni()");
        }

        // Opções de localização do application.ini
        $configs = array(
            APPLICATION_PATH . "/../configs/$file_prefix.ini",
            APPLICATION_PATH . "/configs/$file_prefix.ini"
        );

        // Verifica se a constante da marca (BFFC) esta definida
        if (defined('MARCA')) {
            if (is_numeric(MARCA)) {
                $configs[] = APPLICATION_PATH . "/../configs/$file_prefix.".BFFC_Marca::getCssClass(MARCA).".ini";
                $configs[] = APPLICATION_PATH . "/configs/$file_prefix.".BFFC_Marca::getCssClass(MARCA).".ini";
            } else {
                $configs[] = APPLICATION_PATH . "/../configs/$file_prefix.".MARCA.".ini";
                $configs[] = APPLICATION_PATH . "/configs/$file_prefix.".MARCA.".ini";
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
            throw new Exception("Nenhum arquivo de configuração $file_prefix.ini encontrado do diretório '/configs' $marca em RW_Config::getApplicationIni()");
        }

        // Verifica o ambiente
        $section = (empty($section)) ? APPLICATION_ENV : $section;

        // Instância o arquivo aplication.ini
        return new Zend_Config_Ini($configpath, $section);
    }

    /**
     * Retorna as congirações defindas no application.ini
     *
     * @param string $section OPCIONAL section a ser usanda, se não informada será usado o que está definido em APPLICATION_ENV
     * 
     * @return Zend_Config_Ini
     */
    static public function getApplicationIni($section = null)
    {
        return self::getIni('application', $section);
    }
}