<?php
/**
 * Bootstrap para os testes do PHPUnit
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
 */

// Define path to application directory
defined('TEST_ROOT')
    || define('TEST_ROOT', realpath(__DIR__));

// Define application environment
define('APPLICATION_ENV', 'testing');

if (getenv('TRAVIS') !== false) {
    define('MARCA', 'travis');
}

require_once __DIR__ . '/../vendor/autoload.php';

require_once 'library/TestAssets/BaseTestCase.php';

Zend_Loader_Autoloader::getInstance();

$locale = new Zend_Locale('pt_BR');
Zend_Locale_Format::setOptions(array('precision'=>2));
Zend_Registry::set('Zend_Locale', $locale);
date_default_timezone_set("America/Sao_Paulo");
