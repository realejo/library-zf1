<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
define('APPLICATION_ENV','testing');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(realpath(dirname(__FILE__) . '/../library')),
    realpath('/srv/sites/library/Zend/1.11.11-optimized'),
    get_include_path()
)));


require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();
