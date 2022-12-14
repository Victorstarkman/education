<?php

namespace Service\Bot;

use Service\Request\RequestServer;
use Service\LogService;
use Service\Treatment\TreatmentService;

class Solicitud
{
    public const APP_VERSION = '1.4.4';

    public const HEADER_DEFAULT = [
        "Accept" => "application/json, text/plain, */*",
        "Host" => "misaplicaciones5.abc.gob.ar",
        "Accept" => "application/json, text/plain, */*",
        "Accept-Language" => "pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3",
        "Accept-Encoding" => "gzip, deflate, br",
        "Connection" => "keep-alive",
        "Referer" => "https://misaplicaciones5.abc.gob.ar/LicenciasMedicasWeb/index.html",
        "Sec-Fetch-Dest" => "empty",
        "Sec-Fetch-Mode" => "cors",
        "Sec-Fetch-Site" => "same-origin"
    ];

    private $token;

    private $pathFile = "\\File\\DatosTratados";

    private $positionPath = 0;

    private $pathUsers = "\\File\\Users";

    /**
     * @var RequestServer $Request
     */
    private $Request;

    /**
     * @var LogService $LogService
     */
    private $LogService;

    /**
     * @var TreatmentService $TreatmentService
     */
    private $TreatmentService;

    public function __construct($path)
    {
        $this->Request = new RequestServer();
        $this->LogService = new LogService($path);
        $this->TreatmentService = new TreatmentService($path);
        $this->path = $path;
        $this->pathFile = $path.$this->pathFile;
        $this->pathUsers = $path.$this->pathUsers;
    }

    public function run($token){
        if(empty($token)){
            throw new \Exception('Token is empty');
        }
        $this->token = $token;

        $files = $this->getFiles();

        if(empty($files)){
            return false;
        }

        foreach($files as $file){
            $content = $this->getFileJson($file);
            $this->runContent($content);
            $this->moveFile($file);
        }

    }

    private function getFiles(){
        $files = scandir($this->pathFile."\\");
        $files = array_diff($files, array('.', '..'));

        if(empty($files)){
            return false;
        }

        return $files;
    }

    private function countPathInUser(){
        if(file_exists($this->pathUsers."\\".date('Y-m-d'))){
            $files = scandir($this->pathUsers."\\".date('Y-m-d'));
            $files = array_diff($files, array('.', '..'));
            $this->positionPath = count($files);
        }
    }

    private function getFileJson($file){
        $content = file_get_contents($this->pathFile.$file);
        $content = json_decode($content);

        if(empty($content)){
            return false;
        }

        return $content;
    }

    private function runContent($content){
        $this->countPathInUser();

        foreach($content as $item){
            $id = $item->solicitudLicencia->id;

            $response = $this->requestGetSolictud($id);

            if(empty($response)){
                return false;
            }

            $pathName = $this->creatingPathUsers($response,$id);

            $imags = $this->requestGetImage($id);

            $this->saveImg($pathName,$imags);
            $this->saveJson($response, $item,$pathName);
        }
    }

    private function requestGetSolictud($id){
        $url = "https://misaplicaciones5.abc.gob.ar/wsLicenciasMedicas/cargo/solicitud?=&id={$id}";
        $header['headers'] = self::HEADER_DEFAULT;
        $header['headers']['Authorization'] = "Bearer " . $this->token;
        $header['headers']['app-version'] = self::APP_VERSION;

        $response = $this->Request->request($url, $url, 'GET', $header);

        if(empty($response)){
            return false;
        }

        $json = json_decode($response);

        if(empty($json)){
            return false;
        }

        return $json;
    }

    private function creatingPathUsers($response,$id) {
        $date = date('Y-m-d');
        foreach ($response as $key => $value) {
            $pathName = $value->solicitudLicencia->agente->cuil;
            $pathName = str_replace(' ', '_', $pathName);

            $this->creatingPath($date, $pathName, $id);
        }

        return $this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}";
    }

    private function creatingPath($date, $pathName, $id){

        if(!file_exists($this->path."\\File\\Users\\{$date}")){
            mkdir($this->path."\\File\\Users\\{$date}\\");
        }

        if(!file_exists($this->path."\\File\\Users\\{$date}\\{$this->positionPath}")){
            mkdir($this->path."\\File\\Users\\{$date}\\{$this->positionPath}");
        }

        if(!file_exists($this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}")){
            mkdir($this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}");
        }

        if(!file_exists($this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}")){
            mkdir($this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}");
        }

        if(!file_exists($this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}\\img")){
            mkdir($this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}\\img");
        }

        if(!file_exists($this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}\\json")){
            mkdir($this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}\\json");
        }

        if(!file_exists($this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}\\json\\consultarDatos")){
            mkdir($this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}\\json\\consultarDatos");
        }

        return $this->path."\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}";
    }

    private function requestGetImage($id) {
        $url = "https://misaplicaciones5.abc.gob.ar/wsLicenciasMedicas/documentacion/getnames?&id={$id}";
        $header['headers'] = self::HEADER_DEFAULT;
        $header['headers']['Authorization'] = "Bearer " . $this->token;
        $header['headers']['app-version'] = self::APP_VERSION;

        $response = $this->Request->request($url, $url, 'GET', $header);

        if(empty($response)){
            return false;
        }

        $json = json_decode($response);

        if(empty($json)){
            return false;
        }

        foreach($json as $key =>$filename) {
            $url = "https://misaplicaciones5.abc.gob.ar/wsLicenciasMedicas/documentacion/get?=&id={$id}&filename={$filename}";

            $img[] = $this->Request->request($url, $url, 'GET', $header);
        }

        return $img;
    }

    private function saveImg($pathName, $imgArray) {

        foreach($imgArray as $key => $img) {
            $filename = $key . '.jpg';
            $path = "{$pathName}\\img\\{$filename}";

            file_put_contents($path, $img);
        }
    }

    private function saveJson($response, $jsonOriginResponse, $pathName) {
        foreach($response as $key => $item)
        {
            $filename = "{$item->id}_{$item->idReg}.json";
            $path = "{$pathName}\\json\\consultarDatos\\{$filename}";
            $item = $this->TreatmentService->treatmentJsonConsultarDatos($item,$path);
            $item['codigoRegEstat'] = $this->TreatmentService->convertCodigoRegEstat($item['codigoRegEstat'] ?? null);

            file_put_contents($path, json_encode($item));
        }

        $filename = "jsonOriginResponse.json";
        $path = "{$pathName}\\json\\{$filename}";

        file_put_contents($path, json_encode($jsonOriginResponse));
    }

    private function moveFile($file){
        $path = $this->path.'\\File\\Users\\'.date('Y-m-d').'\\'.$this->positionPath.'\\';
        $pathFile = $path.$file;

        if(!file_exists($path)){
            mkdir($path);
        }

        rename($this->pathFile.$file, $pathFile);
    }

    private function convertCodigoRegEstat($json) {
        foreach($json as $key => $item) {

        }

        return $json;
    }
}
