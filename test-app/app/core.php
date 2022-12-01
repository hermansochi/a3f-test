<?php
declare(strict_types = 1);

use Core\Html\Tag;
use Core\Html\Page;

define('START', microtime(true));

echo 'App running' . PHP_EOL;

$url = $argv[1];
if ($url === '') {
	die();
}
printf("Parsing:  %s\n", $url);

$page = new Page($url);

$pageContent = $page->getContent();
//echo $pageContent . PHP_EOL;
printf("Beginning of the HTML code:\n %-256.256s...\n", $pageContent);

$arr = $page->parseStageOne();
var_dump($arr);

$arr = $page->parseStageTwo();

var_dump($arr);

printf("Execution time: %f\n", microtime(true) - START);
