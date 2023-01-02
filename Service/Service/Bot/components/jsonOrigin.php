<?php

include __DIR__."/../Config/ConfigEnv.php";

$pathTreatment = getenv('PATHFBOOT')."Treatment/";
$pathPages = getenv('PATHFBOOT')."pages/0/";
$josnPages = scandir($pathPages);
$josnPages = array_diff($josnPages, array('.', '..'));
$josnPages = end($josnPages);
$filejosnPages = $pathPages.$josnPages;
$josnPages = json_decode(file_get_contents($filejosnPages), true);

$pathTreatment = scandir($pathTreatment);
$pathTreatment = array_diff($pathTreatment, array('.', '..'));

function savejsonOrinResponse($jsonOrigin, $caminho){
    $file = fopen($caminho."jsonOrinResponse.json", "w");
    fwrite($file, json_encode($jsonOrigin));
    fclose($file);
}

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
                $json = json_decode(file_get_contents($filepath), true);
                foreach($josnPages['content'] as $key => $value) {

                    if($value['solicitudLicencia']['id'] == $json[0]['solicitudLicencia']['id']){
                        $patJsonOriginDist=getenv('PATHFBOOT')."Treatment/".$vt."/".$vp.'/'.$vj.'/json/';
                        savejsonOrinResponse($value,$patJsonOriginDist);
                        echo "Save jsonOrigin in: ".$patJsonOriginDist."\n";
                        break;
                    }
                }

            }
        }
    }


}
echo "Finish Treatment \n";

