<?php
declare(strict_types = 1);

// autoloader
spl_autoload_register(function($class) {
	$root = $_SERVER['DOCUMENT_ROOT'];
	$ds = DIRECTORY_SEPARATOR;
	
	$filename = $root . $ds . str_replace('\\', $ds, $class) . '.php';
	require($filename);
});

require('core.php');