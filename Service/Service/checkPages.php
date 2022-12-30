<?php
$config = __DIR__ . '/Bot/Config/ConfigEnv.php';
include $config;
$path = getenv('PATHFBOOT')."Treatment/";
$paths = scandir($path);
$paths = array_diff($paths, array('.', '..'));
$count = 0;
foreach ($paths as $key => $value) {
    $newpath = getenv('PATHFBOOT')."Treatment/".$value."/";
    $newpath = scandir($newpath);
    $newpath = array_diff($newpath, array('.', '..'));
    foreach($newpath as $k=>$v){
        $vn = getenv('PATHFBOOT')."Treatment/".$value."/".$v;
        $vn = scandir($vn);
        $vn = array_diff($vn, array('.', '..'));
        foreach($vn as $keyFile=>$file){
            echo $file."\n";die;
        }
        $count = $count + count($vn);
    }
}
echo "Total Pages: ".$count."\n";
