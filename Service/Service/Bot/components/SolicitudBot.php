<?php

namespace Service\Bot\components;

use Service\Request\RequestServer;
use Handlers\Pages\Handlers;
use Repository\File\SaveFile;
use Repository\Log\Failure;
use Repository\Pages\Page;

class SolicitudBot
{

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

    private $retry = 0;
    private $maxRetry = 10;
    private $retrySleep = 10;
    private string $token = 'Bearer ';
    private string $APP_VERSION;
    private array $pages = [];
    private int $size;

    private RequestServer $Request;
    private Handlers $Handlers;
    private SaveFile $Files;
    private Failure $Failure;
    private Page $page;

    public function __construct(int $size = 20)
    {
        $this->Request = new RequestServer();
        $this->Handlers = new Handlers();
        $this->Files = new SaveFile();
        $this->Failure = new Failure();
        $this->page = new Page();

        $this->APP_VERSION = getenv('APP_VERSION', '1.4.4');
        $this->size = $size;
    }

    public function scrapingSolicitud($token)
    {
        echo "\r\n set token in solicitudBot\r\n";
        $this->token .= $token;
        $paths = $this->Files->getPathPageDate();
        echo "\r\n get pages \r\n";
        $this->pages = $this->page->getPage();


        if (empty($this->pages)) {
            $this->Failure->prepareLog('No se encontro la paginas', __FILE__, __LINE__, $this->pages);
            die('No se encontro la paginas.');
        }

        echo "\r\n update scraping solicited end para false \r\n";
        $this->page->updateEnd(false);

        foreach ($paths as $path) {
            $path = explode('/', $path);
            if (empty($path[end($path)])) {
                array_pop($path);
            }
            array_pop($path);
            $path = implode('/', $path);
            $pagesPath = $this->Files->getPathPages($path);
            foreach ($pagesPath as $pagePath) {
                //pegar somente o numero da pasta que esta no nome do arquivo no
                $page = explode('/', $pagePath);
                if (empty($page[end($page)])) {
                    array_pop($page);
                }
                $page = end($page);

                foreach ($this->Files->getPathFilesPage($pagePath) as $file) {
                    $this->startScaping($page, $this->Files->getFile($file));
                }
            }
        }

        $this->page->updateEnd(true);
        die('scrapingSolicitud end');
    }

    private function startScaping(int $page, $files)
    {
        echo 'Tratameto Page: ' . $page . PHP_EOL;
        if (is_string($files)) {
            $files = json_decode($files, true);
            $this->startScaping($page, $files);
            return;
        } elseif (empty($files)) {
            $this->retry++;
            if ($this->retry > $this->maxRetry) {
                $this->Failure->prepareLog('No se encontro el archivo page: ' . $page, __FILE__, __LINE__);
                $this->Handlers->deletLogToken('No se encontro el archivo page: ' . $page);
                $this->Files->deletePathAndFilies($page);

                throw new \Exception('No se encontro el archivo page: ' . $page);
            } else {
                echo "\r\n retry " . $this->retry . " sleep " . $this->retrySleep . " retryMax " . $this->maxRetry . " " . __LINE__ . " \r\n";
                sleep($this->retrySleep);
                $this->startScaping($page, $files);
                die;
            }
        }

        $this->retry = 0;
        $IDSjSONoRIGIN = [];
        foreach ($files as $file) {

            if (isset($file['solicitudLicencia'])) {
                $id = $file['solicitudLicencia']['id'];
                echo "scrap path:{$id} \n";
                $jsonFile = $this->requestDataSolictud($file['solicitudLicencia']['id'], true);
            } else {
                $this->Failure->prepareLog('No se encontro la solicitud de licencia page: ' . $page, __FILE__, __LINE__, $file);
                $jsonFile = [];
                throw new \Exception('No se encontro la solicitud de licencia page: ' . $page);
            }

            if (!empty($jsonFile)) {
                if (is_string($jsonFile) || is_object($jsonFile)) {
                    if (is_object($jsonFile)) {
                        $jsonFile = json_decode(json_encode($jsonFile), true);
                    } else {
                        $jsonFile = json_decode($jsonFile, true);
                    }
                }
                if (!is_array($jsonFile)) {
                    $this->retry++;
                    if ($this->retry >= $this->maxRetry) {
                        $this->Failure->prepareLog('No se encontro la solicitud de licencia page: ' . $page, __FILE__, __LINE__, [$jsonFile]);
                        $this->Handlers->deletLogToken('No se encontro la solicitud de licencia page: ' . $page);
                        throw new \Exception('No se encontro la solicitud de licencia page: ' . $page);
                    }else{
                        echo "\r\n retry " . $this->retry . " sleep " . $this->retrySleep . " retryMax " . $this->maxRetry . " " . __LINE__ . " \r\n";
                        sleep($this->retrySleep);
                        $this->startScaping($page, $files);
                        die;
                    }
                }

                $this->retry = 0;

                $data = $this->standardizeData($jsonFile);
                $dataEncode = json_encode($data);
                $IDSjSONoRIGIN[] = $data[0]['id'];
                if($this->checkPast($data[0]['idReg'])){
                    $dataId=$data[0]['id'];
                    echo "\r\n pulando donwload pois ja foi baixado: {$dataId} \r\n";
                    continue;
                }
                $this->Files->createFilesSolicitedJson($page, $data[0]['id'], $data[0]['idReg'], $dataEncode);
                foreach ($this->requestGetImage($data[0]['solicitudLicencia']['id'], false) as $key => $image) {
                    $nameImg = $data[0]['id'] . '_' . $key . '.jpg';
                    $this->Files->createImgFile($page, $nameImg, $data[0]['id'], $image);
                }
                $totalDownload = count($IDSjSONoRIGIN);
                $this->pages['total_file_downloaded'] = $totalDownload;
                $this->page->updateFileDownload($this->pages['id'], $totalDownload);
            }
        }
        $this->Files->deleteAndMovePathAndFilies($page, $IDSjSONoRIGIN);
        $this->Files->movePathUsersForSolicited();

    }

    private function checkPast(int $id){
        $path = getenv('PATHFBOOT')."Treatment/";

        $paths = scandir($path);
        $paths = array_diff($paths, array('.', '..'));
        foreach ($paths as $key => $value) {
            $newpath = getenv('PATHFBOOT')."Treatment/".$value."/";
            $newpath = scandir($newpath);
            $newpath = array_diff($newpath, array('.', '..'));
            foreach($newpath as $k=>$v){
                $vn = getenv('PATHFBOOT')."Treatment/".$value."/".$v;
                $vn = scandir($vn);
                $vn = array_diff($vn, array('.', '..'));



                if(in_array($v,$vn)){
                    return true;
                }
            }

        }
        return false;
    }

    private function standardizeData(array $data)
    {
        $newData = $data;
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $newData[$key] = $this->standardizeData($value);
            } else {
                if ($value) {

                    if (is_array($value) || is_object($value)) {
                        $value = json_decode(json_encode($value), true);
                        $newData[$key] = $this->standardizeData($value);
                    } else {
                        $newData[$key] = $this->Handlers->removeSpace($value);
                        $newData[$key] = $this->Handlers->convetDate($key, $newData[$key]);
                        $newData[$key] = $this->Handlers->convertCodigoRegEstat($key, $newData[$key]);
                    }
                } else {
                    $newData[$key] = $value;
                }
            }
        }

        return $newData;
    }

    private function requestDataSolictud($id, $sleep = false)
    {
        $url = "https://misaplicaciones5.abc.gob.ar/wsLicenciasMedicas/cargo/solicitud?=&id={$id}";
        $header['headers'] = self::HEADER_DEFAULT;
        $header['headers']['Authorization'] = "Bearer " . $this->token;
        $header['headers']['app-version'] = $this->APP_VERSION;

        return $this->Request->request($url, $url, 'GET', $header, $sleep);
    }

    private function requestGetImage($id, $sleep = false)
    {
        $url = "https://misaplicaciones5.abc.gob.ar/wsLicenciasMedicas/documentacion/getnames?&id={$id}";
        $header['headers'] = self::HEADER_DEFAULT;
        $header['headers']['Authorization'] = "Bearer " . $this->token;
        $header['headers']['app-version'] = $this->APP_VERSION;

        $response = $this->Request->request($url, $url, 'GET', $header, $sleep);

        $json = json_decode($response, true) ?? [];

        foreach ($json as $key => $filename) {
            $url = "https://misaplicaciones5.abc.gob.ar/wsLicenciasMedicas/documentacion/get?=&id={$id}&filename={$filename}";

            $img[] = $this->Request->request($url, $url, 'GET', $header, $sleep);
        }

        return $img ?? [];
    }
}
