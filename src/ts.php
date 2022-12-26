<?php

require_once __DIR__.'/vendor/autoload.php';

use sql\genericsqlformat\Select\Select;

$select = new Select(true);
$select->setFrom('user_login');
$select->setColumns(['id']);
print_r($select->run());