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
    private $saltPage = 0;
    private $maxSaltPage = 10;

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
        if (!getenv('REQUEST_ALL') && $isNewScraping) {
            echo "\r\n Request all off \r\n";
            $numPerPage = 20;
        } elseif (getenv('REQUEST_ALL') == 'false') {
            $numPerPage = $this->size;
        } else {
            $numPerPage = $this->page->getTotalElements();
            $page = 0;
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
        $pages['current_page'] = 0;
        $this->scraping($pages);
        $this->page->updateTermino(true);
    }

    private function scraping(array $pages)
    {
        for ($i = $pages['current_page']; $i <= $pages['page_total']; $i++) {

            if ($this->saltPage >= $this->maxSaltPage) {
                break;
            }

            if (getenv('REQUEST_ALL') == 'true') {
                $i = 0;
            }

            $data = json_decode($this->requestPages($i, true), true)['content'] ?? [];

            if (empty($data)) {
                $this->logFailure->prepareLog('scraping pageEmpty', __FILE__, __LINE__, $data);
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
            echo "Limpiando datos \r\n";
            foreach ($data as $key => $value) {
                if ($this->SaveFile->checkPagesAll($value['id'])) {
                    unset($data[$key]);
                }
            }

            if (empty($data)) {
                echo "No hay datos para descargar \r\n";
                $this->saltPage++;
                continue;
            }

            $this->saltPage = 0;
            echo "\r\n Save page: {$i} new conteudo:" . count($data) . " \r\n";
            $this->SaveFile->createFilesPages($i, json_encode($data, JSON_PRETTY_PRINT));
            $this->SaveFile->saveJsonPagePendente($data);
            $this->page->insertPercentageOfProgress();
            if ($pages['termino']) {
                $page = $pages['page_total'] - 1;
            } else {
                $page = $i + 1;
            }

            if (getenv('REQUEST_ALL') == 'true') {
                echo "\r\n REQUEST_ALL \r\n";
                $this->page->updateCurrentPage($pages['id'], $pages['page_total'], $pages['total_file_downloaded']);
                break;
            }

            $this->page->updateCurrentPage($pages['id'], $page, $pages['total_file_downloaded']);
            echo "\r\n Actual_page: {$i}, next_page: {$page} \r\n";
        }
    }
}
