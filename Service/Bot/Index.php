<?php

namespace Service\Bot;

use Service\Request\RequestServer;
use Service\LogService;

class Index
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

    /**
     * @var RequestServer $Request
     */
    private $Request;

    /**
     * @var LogService $LogService
     */
    private $LogService;

    public function __construct()
    {
        $this->Request = new RequestServer();
        $this->LogService = new LogService();
    }

    public function run($token){
        if(empty($token)){
            $this->LogService->setLog([
                'message' => 'la token está vacía',
                'function' => 'run',
            ], 'Failure', 'Index');

            throw new \Exception('la token está vacía');
        }

        $this->token = $token;

        $body = $this->requestPageNoAprovadas(0,20);
        $json = json_decode($body);

        if(empty($json)){
            $this->LogService->setLog([
                'message' => 'la json está vacía',
                'function' => 'run',
            ], 'Failure', 'Index');

            throw new \Exception('la json está vacía');
        }

        for($page=0; $page < $json->totalPages; $page++){
            $body = $this->requestPageNoAprovadas($page,20);
            $jsonBody = json_decode($body);

            if(empty($jsonBody)){
                $this->LogService->setLog([
                    'message' => 'la jsonBody está vacía',
                    'function' => 'run',
                ], 'Failure', 'Index');

                throw new \Exception('la jsonBody está vacía');
            }

            $content = $this->checkContentExistInFile($jsonBody->content);

            if(empty($content)){
                break;
            }

            $this->saveFileInJson($content);
            $this->saveIdLogSucces($content);

        }

        return true;
    }

    private function requestPageNoAprovadas($page = 0, $numPerPage=20) {
        return $this->Request->request("https://misaplicaciones5.abc.gob.ar/wsLicenciasMedicas/solicitudestado/noAprobadas?&page={$page}&numPerPage={$numPerPage}&sortField=solicitudLicencia.fechaCreacion&sortDir=DESC&filterType=&filterData=&version=PRESTADORA", 'https://misaplicaciones5.abc.gob.ar/LicenciasMedicasWeb/index.html', 'GET', [
            'headers' => array_merge(self::HEADER_DEFAULT, ['Authorization' => 'Bearer '.$this->token, 'app-version' => self::APP_VERSION]),

        ]);
    }

    private function saveFileInJson($content){
        $date = date('Y-m-d H:i:s');
        $file = "File/PageNoAprovadas/{$date}.json";
        if(file_exists($file)){
            $file = file_get_contents($file);
            $json = json_decode($file);
            $content = array_merge($json, $content);
        }else{
            $content = $content;
        }
        file_put_contents($file, json_encode($content));

    }

    private function saveIdLogSucces($conten){

        if(!is_array($conten)){
            $json = json_decode($conten, true);
        }else{
            $json = $conten;
        }



        if(empty($json)){
            $this->LogService->setLog([
                'message' => 'la json conten está vacía',
                'function' => 'saveIdLogSucces',
            ], 'Failure', 'Index');

            throw new \Exception('la json conten está vacía');
        }

        foreach($json as $key => $value){
            $this->LogService->setLog([
                'id' => $value->id,
                'function' => 'saveIdLogSucces',
            ], 'Success', 'Index');
        }
    }

    private function checkContentExistInFile($content){
        $path = "Logs/Success/";
        $files = scandir($path);
        $files = array_diff($files, array('.', '..'));

        if(empty($files)){
            return $content;
        }

        foreach($files as $file){
            $file = file_get_contents($path.$file);
            $json = json_decode($file);
            foreach($json as $key => $value){
                foreach($content as $keyContent => $valueContent){
                    if($value->id == $valueContent->id){
                        unset($content[$keyContent]);
                    }
                }
            }
        }

        if(empty($content)){
            return false;
        }

        return $content;
    }
}