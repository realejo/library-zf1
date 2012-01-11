<?php
/**
 * CKEDITOR helper
 *
 * @category   RW
 * @package    RW_View
 * @subpackage Helper
 * @author     Realejo
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 *
 * @uses viewHelper Zend_View_Helper
 *
 * @todo verificar o JQuery Adapter
 */
class RW_View_Helper_CKEDITOR extends Zend_View_Helper_Abstract
{

    private $_ckeditor;
    private $_ckfinder;
    private $_useJQuery = false;

    /**
     * Reduz o texto eliminando o html
     *
     * @param string $texto texto a ser resumido
     * @param int $size número de caracteres máximo
     *
     */
    public function CKEDITOR($campos, $view, $options = null) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/../configs/application.ini", APPLICATION_ENV);
        $ckeditor = $this->_ckeditor = '/admin/js/_' . $config->cms->htmleditor->ckeditor;
        $ckfinder = $this->_ckfinder = '/admin/js/_' . $config->cms->htmleditor->ckfinder;

        if ( !is_array($campos) && is_string($campos) ) $campos = array($campos);

        if ( !is_null($options) && is_array($options)) {
            if (isset($options['useJQuery'])) $this->_useJQuery = (bool) $options['useJQuery'];
        }

        $configs = '';
        if ($this->_useJQuery) {
            $configs = '';
            foreach($campos as $c)
                $configs .= $this->_getConfig_JQuery($c);

            $html = <<<JQUERY
                $(document).ready(function(){
                    $.getScript("$ckeditor/ckeditor_basic.js", function(){
                        $.getScript("$ckeditor/adapters/jquery.js", function(){
                            $configs
                        });
                    });
                });
JQUERY;

        } else {

            $view->headScript()->appendFile(
                $ckeditor . '/ckeditor_basic.js',
                'text/javascript'
            );
            foreach($campos as $c)
                $configs .= $this->_getConfig_HTML($c);

            $html = <<<HTML
                $(document).ready(function(){
                    $configs
                });
HTML;
        }

        return $html;
    }

    private function _getConfig_HTML($campo) {
        $ckfinder = $this->_ckfinder;

        $html = <<<HTML
           CKEDITOR.replace( '$campo', {
                filebrowserBrowseUrl      : '$ckfinder/ckfinder.html',
                filebrowserImageBrowseUrl : '$ckfinder/ckfinder.html?Type=Images',
                filebrowserUploadUrl      : '$ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl : '$ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
            });
HTML;

        return $html;
    }

    private function _getConfig_JQuery($campo) {
        $ckfinder = $this->_ckfinder;

        $html = <<<JQUERY
           $( '$campo' ).ckeditor(function(){ $.noop(), {
                filebrowserBrowseUrl      : '$ckfinder/ckfinder.html',
                filebrowserImageBrowseUrl : '$ckfinder/ckfinder.html?Type=Images',
                filebrowserUploadUrl      : '$ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl : '$ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
            }});
JQUERY;

        return $html;
    }}
