<?php
/**
 * CKEDITOR Helper
 *
 * Cria o JavaScript necessário para utilizar o CKEditor*
 *
 * @category   RW
 * @package    RW_View
 * @subpackage Helper
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 *
 * @uses viewHelper Zend_View_Helper
 */
class RW_View_Helper_CKEDITOR extends Zend_View_Helper_Abstract
{

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
        // Localiza o arquivo de configuração
        $config = realpath(APPLICATION_PATH . "/configs/application.ini");
        if (empty($config)) { $config = realpath(APPLICATION_PATH . "/../configs/application.ini"); }
        if (empty($config)) { throw new Exception ('Arquivo de configuração não encontrado em RW_View_Helper_CKEDITOR'); }

        // Carrega a configuração do Application
        $config = new Zend_Config_Ini($config, APPLICATION_ENV);

        // Verifica a versão do CKEditor
        if ( isset($config->cms->htmleditor->ckeditor) ) {
            $ckeditor = $this->_ckeditor = '/js/_' . $config->cms->htmleditor->ckeditor;
        } else {
            throw new Exception('Configuração do CKEditor não encontrada no application.ini em RW_View_Helper_CKEDITOR');
        }

        // Verifica se deve usar o CKFinder
        if (isset($config->cms->htmleditor->ckfinder) && !empty($config->cms->htmleditor->ckfinder)) {
            $ckfinder = '/js/_' . $config->cms->htmleditor->ckfinder;
        } else {
            $ckfinder = false;
        }

        // Verifica os inputs que deve colocar o CKEditor
        if ( !is_array($campos) && is_string($campos) ) $campos = array($campos);

        // Cria a configuração da opções
        $options = array();

        // Verifica o CKFinder
        if ($ckfinder !== false) {
            $options['filebrowserBrowseUrl']       = $this->_ckfinder . '/ckfinder.html';
            $options['filebrowserImageBrowseUrl']  = $this->_ckfinder . '/ckfinder.html?Type=Images';
            $options['filebrowserUploadUrl']       = $this->_ckfinder . '/core/connector/php/connector.php?command=QuickUpload&type=Files';
            $options['filebrowserImageUploadUrl']  = $this->_ckfinder . '/core/connector/php/connector.php?command=QuickUpload&type=Images';
        }

        // Verifica as outras opções
        if (!empty($userOptions) && is_array($userOptions) )
            $options = array_replace($options , $userOptions);

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
        $this->view->headScript()->appendFile($ckeditor . '/ckeditor.js');

        // Carrega o JQuery Adapter
        $this->view->headScript()->appendFile($ckeditor . '/adapters/jquery.js');

        // Carrega o código CKEditor
        $this->view->headScript()->appendScript($script);
    }
}
