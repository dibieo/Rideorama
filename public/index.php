<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run


//$config = apc_fetch('my_config');
//    if (!is_array($config)) {
//require_once 'Zend/Config/Ini.php';
//$section = APPLICATION_ENV;
//$filename = APPLICATION_PATH . '/configs/application.ini';
//$config = new Zend_Config_Ini($filename, $section);
//$config = $config->toArray();
//apc_store('my_config', $config, 600);
//}
//// Create application, bootstrap, and run
//$application = new Zend_Application(
//APPLICATION_ENV,
//$config
//);

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();