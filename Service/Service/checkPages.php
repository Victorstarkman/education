<?php

//contar a quantidade de pasta no diretorio
$dir=__DIR__.'/../File/Treatment/0';
$dir = scandir($dir);
$dir = array_diff($dir, array('..', '.'));
$dir = count($dir);

echo "Quantidade de pastas: ".$dir. "\n";
