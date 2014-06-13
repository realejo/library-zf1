<?php
/**
 * Bootstrap para os testes do PHPUnit
 *
 * @link      http://github.com/realejo/library-zf1
 * @copyright Copyright (c) 2011-2014 Realejo Design Ltda. (http://www.realejo.com.br)
 */
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(__DIR__. '/assets/application'));

// Define application environment
define('APPLICATION_ENV', 'testing');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(realpath(dirname(__FILE__) . '/../library')),
    get_include_path()
)));

require_once 'BaseTestCase.php';
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

$locale = new Zend_Locale('pt_BR');
Zend_Locale_Format::setOptions(array('precision'=>2));
Zend_Registry::set('Zend_Locale', $locale);
date_default_timezone_set("America/Sao_Paulo");
