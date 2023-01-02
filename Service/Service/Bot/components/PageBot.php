<?php

namespace Service\Bot\components;

use Repository\Pages\Page;
use Service\Request\RequestServer;
use Handlers\Pages\Handlers;
use Repository\File\SaveFile;
use Repository\Log\Failure;

class PageBot
{
    private $retry = 0;
    private $maxRetry = 10;
    private $retrySleep = 10;

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

    private bool $isNewScraping;
    private string $token = 'Bearer ';
    private int $size;
    private $APP_VERSION;

    private Page $page;
    private RequestServer $Request;
    private Handlers $Handlers;
    private SaveFile $SaveFile;
    private Failure $logFailure;

    public function __construct($size = 20)
    {
        $this->page = new Page();
        $this->Request = new RequestServer();
        $this->Handlers = new Handlers();
        $this->SaveFile = new SaveFile();
        $this->logFailure = new Failure();

        $this->APP_VERSION = getenv('APP_VERSION', '1.4.4');
        $this->size = $size;
    }

    public function scrapingPages($token, $stop = false)
    {
        $this->token .= $token;
        echo "\n Start scraping pages \n";
        $pages = $this->getPage();

        if ($this->isNewScraping && !$stop) {
            $this->newScraping();
            $stop = true;
            $this->scrapingPages($token, $stop);
        } elseif ($this->isNewScraping && $stop) {
            throw new \Exception('it was not possible to insert the pages in the database');
        }

        $this->startScaping();
    }

    private function getPage(): array
    {
        $pages = $this->page->getPage();

        if (empty($pages)) {
            $this->isNewScraping = true;
        } else {
            $this->isNewScraping = false;
        }

        return $pages;
    }



    private function newScraping()
    {
        echo "\n Start new scraping \n";
        $data = json_decode($this->requestPages(0, true), true);
        if (empty($data)) {
            $this->retry++;
            if ($this->retry >= $this->maxRetry) {
                $this->logFailure->prepareLog('scraping pageEmpty', __FILE__, __LINE__);
                $this->Handlers->deletLogToken('scraping pageEmpty');
                throw new \Exception('scraping pageEmpt');
            } else {
                echo "\r\n retry " . $this->retry . " sleep " . $this->retrySleep . " retryMax " . $this->maxRetry . " " . __LINE__ . " \r\n";
                sleep($this->retrySleep);
                $this->newScraping();
                die;
            }
        }
        $this->retry = 0;
        $this->page->insertPage($data['totalPages'], $data['number'], 0, $data['totalElements']);
    }

    private function requestPages(int $page = 0, bool $isNewScraping = false)
    {
        if(!getenv('REQUEST_ALL') && $isNewScraping){
            $numPerPage = 20;
        }else{
            $numPerPage = $this->page->getTotalElements();
        }

        echo "\r\n Request page {$page} numPersPage {$numPerPage} \r\n";

        return $this->Request->request(
            "https://misaplicaciones5.abc.gob.ar/wsLicenciasMedicas/solicitudestado/noAprobadas?&page={$page}&numPerPage={$numPerPage}&sortField=solicitudLicencia.fechaCreacion&sortDir=DESC&filterType=&filterData=&version=PRESTADORA",
            'https://misaplicaciones5.abc.gob.ar/LicenciasMedicasWeb/index.html',
            'GET',
            [
                'headers' => array_merge(self::HEADER_DEFAULT, ['Authorization' => $this->token, 'app-version' => $this->APP_VERSION]),

            ]
        );
    }

    private function startScaping()
    {
        $this->page->updateTermino(false);
        $pages = $this->getPage();
        if (!$pages['termino']) {
            $this->scraping($pages);
            $this->page->updateTermino(true);
        } else {
            echo "\r\n Start scraping terminou: ". $pages['termino'] . " \r\n";
            if ($pages['current_page'] == $pages['page_total']) {
                echo "\r\n No hay mas paginas para descargar \r\n";
                $requestPage = $this->requestPages(0, false);
                $json = json_decode($requestPage, true);
                if ($json['totalElements'] == $pages['total_file_downloaded']) {
                    echo "\r\n No hay mas archivos para descargar \r\n";
                    return;
                } else {
                    if ($pages['current_page'] >= $json['totalPages']) {
                        $this->page->updatePages($pages['id'], $json['totalPages'], $json['totalElements'], $json['totalElements']);
                        $this->scrapingPages($this->token, true);
                        return;
                    } else {
                        $this->page->updatePageTotal($pages['id'], $json['totalPages']);
                        $this->scrapingPages($this->token, true);
                        return;
                    }
                }
            } else {
                $countFiles = $pages['total_file'] - $pages['total_file_downloaded'];

                if ($countFiles <= 0) {
                    $requestPage = $this->requestPages(0, false);
                    $json = json_decode($requestPage, true);
                    if ($json['totalElements'] == $pages['total_file_downloaded']) {
                        echo "\r\n No hay mas archivos para descargar \r\n";
                        return;
                    } else {
                        if ($pages['current_page'] >= $json['totalPages']) {
                            $this->page->updatePages($pages['id'], $json['totalPages'], $json['totalElements'], $json['totalElements']);
                            return;
                        } else {
                            $this->page->updatePageTotal($pages['id'], $json['totalPages']);
                            $this->scrapingPages($this->token, true);
                            return;
                        }
                    }
                    echo "\r\n No hay mas archivos para descargar \r\n";
                    return;
                }

                $newTotalPage = $countFiles / $this->size;
                $pages['old_page_total'] = $pages['page_total'];
                $pages['old_current_page'] = $pages['current_page'];
                $pages['page_total'] = ceil($newTotalPage);
                $pages['current_page'] = 0;
                $this->scraping($pages);

                //update current page
                $actualPage = $pages['old_current_page'] + $pages['page_total'];
                $this->page->updateCurrentPage($pages['id'], $actualPage, $pages['total_file_downloaded']);
            }
        }
        $this->page->updateTermino(true);
    }

    private function scraping(array $pages)
    {
        for ($i = $pages['current_page']; $i <= $pages['page_total']; $i++) {
            $data = json_decode($this->requestPages($i, false), true)['content'] ?? [];

            if (empty($data)) {
                $this->logFailure->prepareLog('scraping pageEmpty', __FILE__, __LINE__,$data);
                $this->retry++;
                if ($this->retry >= $this->maxRetry) {
                    $this->logFailure->prepareLog('scraping pageEmpty', __FILE__, __LINE__);
                    $this->Handlers->deletLogToken('scraping pageEmpty');
                    throw new \Exception('pageEmpty');
                } else {

                    echo "\r\n retry " . $this->retry . " sleep " . $this->retrySleep . " retryMax " . $this->maxRetry . " " . __LINE__ . " \r\n";
                    sleep($this->retrySleep);
                    $this->scraping($pages);
                    die;
                }
            }

            $this->retry = 0;

            $newData = $this->standardizeData($data);
            $this->SaveFile->createFilesPages($i, json_encode($newData, JSON_PRETTY_PRINT));
            if ($pages['termino']) {
                $page = $pages['page_total'] - 1;
            } else {
                $page = $i + 1;
            }
            $this->page->updateCurrentPage($pages['id'], $page, $pages['total_file_downloaded']);


            if(getenv('REQUEST_ALL')){
                echo "\r\n REQUEST_ALL \r\n";
                break;
            }

            echo "\r\n Actual_page: {$i}, next_page: {$page} \r\n";
        }
    }

    private function standardizeData(array $data)
    {
        $newData = $data;
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $newData[$key] = $this->standardizeData($value);
            } else {
                if ($value) {
                    $isJson = json_encode($value, true);
                    if (is_array($isJson)) {
                        $newData[$key] = $this->standardizeData($isJson);
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
}
