<?php
/**
 * CKEDITOR Helper
 *
 * Cria o JavaScript necessário para utilizar o CKEditor*
 *
 * @link      http://github.com/realejo/library-zf1
 * @copyright Copyright (c) 2011-2014 Realejo Design Ltda. (http://www.realejo.com.br)
 */
class RW_View_Helper_CKEDITOR extends Zend_View_Helper_Abstract
{

    // Possíveis paths onde o CKEditor po de encontrado
    protected $availablePaths = array('/js/_', '/vendor/');

    /**
     * Cria o JavaScript necessário para utilizar o CKEditor
     *
     * @param string|array $campos  Nomes dos campos para colocar o CKEditor
     * @param array        $userOptions (OPTIONAL) Configurações extras
     *
     * @return string
     *
     * - Ele sempre considera que o JQuery está presente e irá colocar no document.ready()
     *    Ex: $(document).ready(function(){ Código do CKEDITOR  });
     *
     * - Ele verifica se existe a configuração do ckeditor no application.ini
     *    Ex: cms.htmleditor.ckeditor = 3.6.2
     *
     * - Ele só irá configura o CKFinder se ele estiver definido no application.ini
     *    Ex: cms.htmleditor.ckfinder = 2.1.1
     *
     */
    public function CKEDITOR($campos, $userOptions = null)
    {
        // Opções de localização do application.ini
        $configs = array(
                    APPLICATION_PATH . "/../configs/application.ini",
                    APPLICATION_PATH . "/configs/application.ini"
                  );

        // Verifica se a constante da marca (BFFC) esta definida
        if (defined('MARCA')) {
            $configs[] = APPLICATION_PATH . "/../configs/application.".BFFC_Marca::getCssClass(MARCA).".ini";
            $configs[] = APPLICATION_PATH . "configs/application.".BFFC_Marca::getCssClass(MARCA).".ini";
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
            require_once 'Zend/Config/Exception.php';
            $marca = (defined('MARCA')) ? '(marca='.BFFC_Marca::getCssClass(MARCA) .')': '' ;
            throw new Exception("Nenhum arquivo de configuração application.ini encontrado do diretório '/configs' $marca");
        }

        // Carrega a configuração do Application
        $config = new Zend_Config_Ini($configpath, APPLICATION_ENV);

        // Verifica a versão do CKEditor
        $ckeditor = false;
        if ( isset($config->cms->htmleditor->ckeditor) ) {
            foreach ($this->availablePaths as $path) {
                if (is_dir(APPLICATION_HTTP . $path. $config->cms->htmleditor->ckeditor)) {
                    $ckeditor = $path. $config->cms->htmleditor->ckeditor;
                    break;
                }
            }
        }

        // Verifica se localizou o CKEditor
        if ($ckeditor === false) {
            throw new Exception('Configuração do CKEditor não encontrada no application'.$marca.'.ini em RW_View_Helper_CKEDITOR');
        }

        // Verifica se deve usar o CKFinder
        $ckfinder = false;
        if (isset($config->cms->htmleditor->ckfinder) && !empty($config->cms->htmleditor->ckfinder)) {
            foreach ($this->availablePaths as $path) {
                if (is_dir(APPLICATION_HTTP . $path. $config->cms->htmleditor->ckfinder)) {
                    $ckeditor = $path. $config->cms->htmleditor->ckfinder;
                    break;
                }
            }
        }

        // Verifica os inputs que deve colocar o CKEditor
        if ( !is_array($campos) && is_string($campos) ) $campos = array($campos);

        // Cria a configuração da opções
        $options = array();

        // Verifica o CKFinder
        if ($ckfinder !== false) {
            $options['filebrowserBrowseUrl']       = $ckfinder . '/ckfinder.html';
            $options['filebrowserImageBrowseUrl']  = $ckfinder . '/ckfinder.html?Type=Images';
            $options['filebrowserUploadUrl']       = $ckfinder . '/core/connector/php/connector.php?command=QuickUpload&type=Files';
            $options['filebrowserImageUploadUrl']  = $ckfinder . '/core/connector/php/connector.php?command=QuickUpload&type=Images';
        }

        // Verifica as outras opções
        if (!empty($userOptions) && is_array($userOptions) ) {
            $options += $userOptions;
        }

        // Formata as configurações
        $options = (empty($options)) ? '{}' : Zend_Json::encode($options);

        // Carrega as opções para cada campo
        $config = '';
        foreach($campos as $c) {
            $config .= "$( '$c' ).ckeditor(function() {}, $options);";
        }

        // Cria a configuração do CKEditor
        $script = "$(document).ready(function(){ $config });";

        // Carrega a biblioteca do CKEditor
        $this->view->headScript()->appendFile($ckeditor . '/ckeditor.js', 'text/javascript', array('minify_disabled' => true));

        // Carrega o JQuery Adapter
        $this->view->headScript()->appendFile($ckeditor . '/adapters/jquery.js', 'text/javascript', array('minify_disabled' => true));

        // Carrega o código CKEditor
        $this->view->headScript()->appendScript($script, 'text/javascript', array('minify_disabled' => true));
    }
}
