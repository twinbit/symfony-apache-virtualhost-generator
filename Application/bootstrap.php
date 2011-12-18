<?php

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__)));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(realpath(APPLICATION_PATH . '/../library'), get_include_path())));

use Symfony\Component\ClassLoader\UniversalClassLoader;
require_once 'Symfony/Component/ClassLoader/UniversalClassLoader.php';

// Register autoloader
$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
	'Symfony' => realpath(APPLICATION_PATH . '/../library'),
	'Application' => realpath(APPLICATION_PATH . '/..')
));
$loader->register();
