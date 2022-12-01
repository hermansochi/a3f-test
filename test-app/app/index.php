<?php
declare(strict_types = 1);

error_reporting(E_ALL);
ini_set('display_errors', 'on');

// autoloader
spl_autoload_register(function($class) {
	$root = __DIR__;
	$ds = DIRECTORY_SEPARATOR;
	
	$filename = str_replace('\\', $ds, $class) . '.php';

	require_once($filename);
});

require_once('core.php');