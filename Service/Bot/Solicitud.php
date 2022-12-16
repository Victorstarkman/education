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
        "Accept-Language" => "es-AR;q=0.8,en-US;q=0.5,en;q=0.3",
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
        $this->pathFile = $path . $this->pathFile;
        $this->pathUsers = $path . $this->pathUsers;
    }

    public function run($token)
    {
        if (empty($token)) {
            throw new \Exception('Token is empty');
        }
        $this->token = $token;

        $files = $this->getFiles();

        if (empty($files)) {
            return false;
        }

        foreach ($files as $file) {
            $content = $this->getFileJson($file);
            $this->runContent($content);
            $this->moveFile($file);
        }
    }

    private function getFiles()
    {
        $files = scandir($this->pathFile . "\\");
        $files = array_diff($files, array('.', '..'));

        if (empty($files)) {
            return false;
        }

        return $files;
    }

    private function countPathInUser()
    {
        if (file_exists($this->pathUsers . "\\" . date('Y-m-d'))) {
            $files = scandir($this->pathUsers . "\\" . date('Y-m-d'));
            $files = array_diff($files, array('.', '..'));
            $this->positionPath = count($files);
        }
    }

    private function getFileJson($file)
    {
        $content = file_get_contents($this->pathFile . $file);
        $content = json_decode($content);

        if (empty($content)) {
            return false;
        }

        return $content;
    }

    private function runContent($content)
    {
        $this->countPathInUser();

        foreach ($content as $item) {
            $id = $item->solicitudLicencia->id;

            $response = $this->requestGetSolictud($id);

            if (empty($response)) {
                return false;
            }

            $pathName = $this->creatingPathUsers($response, $id);

            $imags = $this->requestGetImage($id);

            $this->saveImg($pathName, $imags);
            $this->saveJson($response, $item, $pathName);
        }
    }

    private function requestGetSolictud($id)
    {
        $url = "https://misaplicaciones5.abc.gob.ar/wsLicenciasMedicas/cargo/solicitud?=&id={$id}";
        $header['headers'] = self::HEADER_DEFAULT;
        $header['headers']['Authorization'] = "Bearer " . $this->token;
        $header['headers']['app-version'] = self::APP_VERSION;

        $response = $this->Request->request($url, $url, 'GET', $header);

        if (empty($response)) {
            return false;
        }

        $json = json_decode($response);

        if (empty($json)) {
            return false;
        }

        return $json;
    }

    private function creatingPathUsers($response, $id)
    {
        $date = date('Y-m-d');
        $pathName = str_replace(' ', '_', $response[0]->solicitudLicencia->agente->cuil);
        $path = $this->creatingPath($date, $pathName, $id);

        return $path;
    }

    private function creatingPath($date, $pathName, $id)
    {
        $path = $this->path . "\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}";

        $primePaths = [
            "\\File",
            "\\File\\Users",
            "\\File\\Users\\{$date}",
            "\\File\\Users\\{$date}\\{$this->positionPath}",
            "\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}",
            "\\File\\Users\\{$date}\\{$this->positionPath}\\{$pathName}\\{$id}"
        ];

        foreach ($primePaths as $primePath) {

            if (!file_exists($this->path . "\\{$primePath}")) {
                mkdir($this->path . "\\{$primePath}", 0777, true);
            }
        }

        $subfolders = [
            'img',
            'json',
            'json\\consultarDatos'
        ];

        foreach ($subfolders as $subfolder) {

            if (!file_exists($path . "\\{$subfolder}")) {
                mkdir($path . "\\{$subfolder}", 0777, true);
            }
        }

        return $path;
    }


    private function requestGetImage($id)
    {
        $url = "https://misaplicaciones5.abc.gob.ar/wsLicenciasMedicas/documentacion/getnames?&id={$id}";
        $header['headers'] = self::HEADER_DEFAULT;
        $header['headers']['Authorization'] = "Bearer " . $this->token;
        $header['headers']['app-version'] = self::APP_VERSION;

        $response = $this->Request->request($url, $url, 'GET', $header);

        if (empty($response)) {
            return false;
        }

        $json = json_decode($response);

        if (empty($json)) {
            return false;
        }

        foreach ($json as $key => $filename) {
            $url = "https://misaplicaciones5.abc.gob.ar/wsLicenciasMedicas/documentacion/get?=&id={$id}&filename={$filename}";

            $img[] = $this->Request->request($url, $url, 'GET', $header);
        }

        return $img;
    }

    private function saveImg($pathName, $imgArray)
    {
        $path = "{$pathName}\\img";

        foreach ($imgArray as $key => $img) {
            $filename = "{$key}.jpg";
            $filepath = "{$path}\\{$filename}";
            file_put_contents($filepath, $img);
        }
    }

    private function saveJson($response, $jsonOriginResponse, $pathName)
    {
        $path = "{$pathName}\\json";

        // Salva as respostas tratadas em arquivos JSON individualmente
        foreach ($response as $key => $item) {
            $item = $this->TreatmentService->treatmentJsonConsultarDatos($item, $path);
            $item['codigoRegEstat'] = $this->TreatmentService->convertCodigoRegEstat($item['codigoRegEstat'] ?? null);

            $filename = "{$item->id}_{$item->idReg}.json";
            $filepath = "{$path}\\consultarDatos\\{$filename}";
            file_put_contents($filepath, json_encode($item));
        }

        // Salva a resposta original em um único arquivo JSON
        $filename = "jsonOriginResponse.json";
        $filepath = "{$path}\\{$filename}";
        file_put_contents($filepath, json_encode($jsonOriginResponse));
    }

    private function moveFile($file)
    {
        $targetPath = $this->path . '\\File\\Users\\' . date('Y-m-d') . '\\' . $this->positionPath . '\\';

        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }

        $targetFile = $targetPath . $file;
        $attempts = 0;

        while (!rename($this->pathFile .'\\'. $file, $targetFile) && $attempts < 3) {
            $attempts++;
        }

        if ($attempts == 3) {
            throw new \Exception("Failed to move file after 3 attempts.");
        } else {
            unlink($this->pathFile. '\\' . $file);
        }
    }
}
