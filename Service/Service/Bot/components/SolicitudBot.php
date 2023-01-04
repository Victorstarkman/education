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
    private string $pathDefault;

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
        $this->pathDefault = getenv('PATHFBOOT', '/var/www/filesBot/');
    }

    public function scrapingSolicitud($token)
    {
        echo "\r\n set token in solicitudBot\r\n";
        $this->token .= $token;
        $paths = $this->Files->getPathPageDate();
        echo "\r\n get pages \r\n";
        $this->pages = $this->page->getPage();

        $this->page->insertPercentageOfProgress();

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
                $file = $this->Files->getPathFilesPage($pagePath);
                $file = end($file);
                $explodeNameFile = explode($pagePath, $file);
                $explodeNameFileData = explode('_', end($explodeNameFile));

                $dataPageFile = $explodeNameFileData[0];

                $this->startScaping($page, $this->Files->getFile($file), $dataPageFile);
            }
        }

        $this->page->updateEnd(true);
        $this->page->insertPercentageOfProgress();
        $this->page->saveOnDatabase();
        die('scrapingSolicitud end');
    }

    private function startScaping(int $page, $files, string $dataPageFile)
    {
        echo 'Tratameto Page: ' . $page . PHP_EOL;
        if (is_string($files)) {
            $files = json_decode($files, true);
            $this->startScaping($page, $files, $dataPageFile);
            return;
        } elseif (empty($files)) {
            $this->retry++;
            if ($this->retry > $this->maxRetry) {
                $this->Failure->prepareLog('No se encontro el archivo page: ' . $page, __FILE__, __LINE__);
                $this->Handlers->deletLogToken('No se encontro el archivo page: ' . $page);
                $this->Files->deletePathAndFilies($page, $dataPageFile);

                throw new \Exception('No se encontro el archivo page: ' . $page);
            } else {
                echo "\r\n retry " . $this->retry . " sleep " . $this->retrySleep . " retryMax " . $this->maxRetry . " " . __LINE__ . " \r\n";
                sleep($this->retrySleep);
                $this->startScaping($page, $files, $dataPageFile);
                die;
            }
        }

        $this->retry = 0;
        $IDSjSONoRIGIN = [];
        $proceco = 0;

        foreach ($files as $key => $file) {
            $idPathFile = $file['id'];
            $IDSjSONoRIGIN[] = $idPathFile;
            //calcular o percentual de progresso do processo usando count($files) - IDSjSONoRIGIN
            $proceco = round(((count($IDSjSONoRIGIN)) / count($files) * 100), 2);

            echo "\r ---- progresso: {$proceco}% ---- \r\n";
            if ($this->Files->checkPastTreatment($idPathFile)) {
                echo "\r\n pulando donwload pois ja foi baixado: {$idPathFile} \r\n";
                $this->Files->saveJsonOrigin($idPathFile, $page, $file, $dataPageFile);
                continue;
            }

            if (isset($file['solicitudLicencia'])) {
                echo "scrap path:{$idPathFile} \n";
                $jsonFile = $this->requestDataSolictud($file['solicitudLicencia']['id'], true);
            } else {
                $this->Failure->prepareLog('No se encontro la solicitud de licencia page: ' . $page, __FILE__, __LINE__, $file);
                $jsonFile = [];
                throw new \Exception('No se encontro la solicitud de licencia page: ' . $page);
            }

            if (!empty($jsonFile)) {
                //verificar se o json e uma string  ou objeto para converter em array
                if (is_string($jsonFile) || is_object($jsonFile)) {
                    if (is_object($jsonFile)) {
                        $jsonFile = json_decode(json_encode($jsonFile), true);
                    } else {
                        $jsonFile = json_decode($jsonFile, true);
                    }
                }

                if (empty($jsonFile)) {
                    echo "jsonFile vazio \n";
                    $this->Failure->prepareLog('No se encontro la solicitud de licencia page: ' . $page, __FILE__, __LINE__, [$jsonFile]);
                    $this->Files->saveJsonSucess($idPathFile);
                    $this->page->updateRecordsSalteados();
                    continue;
                }

                if (!is_array($jsonFile)) {
                    $this->retry++;
                    $logError = [
                        "jsonFile" => $jsonFile,
                        "filePath" => $idPathFile,
                        "date" => date('Y-m-d H:i:s'),
                        "token" => $this->token,
                        "page" => $page,
                        "file" => $file,
                        "solictudId" => $file['solicitudLicencia']['id'],
                        "retry" => $this->retry,
                    ];
                    $hashError = md5(json_encode($logError));
                    $this->Failure->prepareLog("HASHERROR:{$hashError} No se encontro la file.", __FILE__, __LINE__, $logError);

                    if ($this->retry >= $this->maxRetry) {
                        $this->Handlers->deletLogToken("HASHERROR:{$hashError} No se encontro la file.");
                        throw new \Exception("HASHERROR:{$hashError} No se encontro la file.");
                    } else {
                        echo "\r\n HASHERROR:{$hashError} No se encontro la file. \r\n";
                        $this->page->updateRecordsSalteados();
                        sleep($this->retrySleep);
                        continue;
                    }
                }

                $this->retry = 0;

                $data = $this->standardizeData($jsonFile);
                $dataEncode = json_encode($data);




                $this->Files->createFilesSolicitedJson($page, $idPathFile, $data[0]['idReg'], $dataEncode, $dataPageFile);
                foreach ($this->requestGetImage($data[0]['solicitudLicencia']['id'], false) as $key => $image) {
                    $nameImg = $data[0]['id'] . '_' . $key . '.jpg';
                    $this->Files->createImgFile($page, $nameImg, $idPathFile, $image, $dataPageFile);
                }

                $this->pages['total_file_downloaded'] = $this->page->updateFileDownload(); //atualiza o total de arquivos baixados
                $this->Files->saveJsonOrigin($idPathFile, $page, $file, $dataPageFile);
                $this->Files->saveJsonSucess($idPathFile);
                $this->page->insertPercentageOfProgress();
            } else {
                echo "jsonFile vazio \n";
                $this->Failure->prepareLog('No se encontro la solicitud de licencia page: ' . $page, __FILE__, __LINE__, [$jsonFile]);
                $this->page->updateRecordsSalteados();
                continue;
            }
        }
        $this->Files->deleteAndMovePathAndFilies($page, $dataPageFile);
        $this->Files->movePathUsersForSolicited($dataPageFile);
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
