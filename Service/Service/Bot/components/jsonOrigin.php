<?php

include __DIR__."/../Config/ConfigEnv.php";

$pathTreatment = getenv('PATHFBOOT')."Treatment/";
$pathPages = getenv('PATHFBOOT')."pages/0";

$pathTreatment = scandir($pathTreatment);
$pathTreatment = array_diff($pathTreatment, array('.', '..'));

foreach($pathTreatment as $vt) {
    $pathTreatmentData = getenv('PATHFBOOT')."Treatment/".$vt;
    $pathTreatmentData = scandir($pathTreatmentData);
    $pathTreatmentData = array_diff($pathTreatmentData, array('.', '..'));

    foreach($pathTreatmentData as $vp){
        $pathTreatmentDataPage = getenv('PATHFBOOT')."Treatment/".$vt."/".$vp;
        $pathTreatmentDataPage = scandir($pathTreatmentDataPage);
        $pathTreatmentDataPage = array_diff($pathTreatmentDataPage, array('.', '..'));
        foreach($pathTreatmentDataPage as $vj){
            $pathTreatmentDataPageJson = getenv('PATHFBOOT')."Treatment/".$vt."/".$vp.'/'.$vj.'/json/consultarDatos';
            //get file in path
            $pathTreatmentDataPageJson = scandir($pathTreatmentDataPageJson);
            $pathTreatmentDataPageJson = array_diff($pathTreatmentDataPageJson, array('.', '..'));
            foreach($pathTreatmentDataPageJson as $file) {
                $filepath = getenv('PATHFBOOT')."Treatment/".$vt."/".$vp.'/'.$vj.'/json/consultarDatos/'.$file;
                $fileJson = fopen($pathPages, "a");
                $json = json_decode($fileJson, true);
                die(var_dump($json));

            }
        }
    }

}
