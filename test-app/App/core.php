<?php
declare(strict_types = 1);

use App\Core\Html\Tag;

echo 'App running';

$tag = new Tag('input');

echo $tag
->setAttr('id', 'test')
->setAttr('class', 'eee bbb')
->open();
