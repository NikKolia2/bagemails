<?php

$menu = [];
$object = $xpdo->newObject(modMenu::class);

$object->fromArray(array(
    'text' => 'BagEmails',
    'parent' => 'components',
    'description' => 'Письма с форм',
    'namespace' => PKG_NAME_LOWER,
    'action' => 'list',
    'menuindex' => '0',
    'params' => '',
    'handler' => '',
),'',true,true);

$menu[] = $object;
return $menu;