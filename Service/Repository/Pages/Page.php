<?php

namespace Repository\Pages;

use Repository\RepositoryBase;

class Page extends RepositoryBase
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getPage(): array
    {
        $this->setFromLogs('Logs_Pages');
        $this->select();
        $this->setColumns(['id', 'current_page', 'page_total', 'end', 'total_file', 'total_file_downloaded']);
        $this->setLimit(1);
        print_r($this->getSelect());
        return $this->getSelect()[0] ?? [];
    }

    public function insertPage(int $pageTotal, int $currentPage, int $totalFileDownloaded, int $total_file): bool
    {

        $this->setFromLogs('Logs_Pages');

        $pages = [
            'id' => 1,
            'totalPages' => $pageTotal,
            'totalRecords' => $total_file,
            'actualPage' => $currentPage,
            'processedPage' => $currentPage,
            'processedRecord' => $totalFileDownloaded,
            'error' => false,
            'message' => '',
            'termino' => false,
            'recordsSalteados' => 0,
            'page_total' => $pageTotal,
            'current_page' => $currentPage,
            'total_file_downloaded' => $totalFileDownloaded,
            'total_file' => $total_file,
            'end' => false,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->insert($pages)) {
            //save on database juli
            $this->saveOnDatabase();
            return true;
        }

        return false;
    }

    public function updateCurrentPage(int $id, int $currentPage, int $totalFileDownloaded): bool
    {
        $this->setFromLogs('Logs_Pages');

        $pages = [
            'current_page' => $currentPage,
            'total_file_downloaded' => $totalFileDownloaded,
            'actualPage' => $currentPage,
            'processedRecord' => $totalFileDownloaded,
            'updated_at' => date('Y-m-d H:i:s'),
            'error' => false,
            'message' => '',
        ];

        if ($this->updateColumn($pages)) {
            //save on database juli
            $this->saveOnDatabase();
            return true;
        }

        return false;
    }

    public function updateTermino(bool $status): bool
    {
        $this->setFromLogs('Logs_Pages');
        $pages = [
            'termino' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            'error' => false,
            'message' => '',
        ];

        if ($this->updateColumn($pages)) {
            //save on database juli
            $this->saveOnDatabase();
            return true;
        }

        return false;
    }

    public function updateEnd(bool $status)
    {

        $this->setFromLogs('Logs_Pages');
        $pages = [
            'end' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            'error' => false,
            'message' => '',
        ];

        if ($this->updateColumn($pages)) {
            //save on database juli
            $this->saveOnDatabase();
            return true;
        }

        return false;
    }

    public function updateTotalPageAndTotalFiles(int $id, int $totalPage, int $totalFile): bool
    {
        $this->setFromLogs('Logs_Pages');
        $pages = [
            'page_total' => $totalPage,
            'total_file' => $totalFile,
            'totalPages' => $totalPage,
            'totalRecords' => $totalFile,
            'updated_at' => date('Y-m-d H:i:s'),
            'error' => false,
            'message' => '',
        ];

        if ($this->updateColumn($pages)) {
            //save on database juli
            $this->saveOnDatabase();
            return true;
        }

        return false;
    }

    public function updateFileDownload(int $id, int $totalDownload): bool
    {
        $processedPage = (($totalDownload / 20) > 0) ? (int)($totalDownload / 20) : 1;

        $this->setFromLogs('Logs_Pages');
        $pages = [
            'total_file_downloaded' => $totalDownload,
            'processedRecord' => $totalDownload,
            'processedPage' => $processedPage,
            'updated_at' => date('Y-m-d H:i:s'),
            'error' => false,
            'message' => '',
        ];

        if ($this->updateColumn($pages)) {
            //save on database juli
            $this->saveOnDatabase();
            return true;
        }

        return false;
    }

    public function updatePages(int $id, int $totalPage, int $totalFile, int $totalDownload): bool
    {
        $this->setFromLogs('Logs_Pages');
        $pages = [
            'page_total' => $totalPage,
            'current_page' => $totalPage,
            'total_file' => $totalFile,
            'total_file_downloaded' => $totalDownload,
            'totalPages' => $totalPage,
            'totalRecords' => $totalFile,
            'processedRecord' => $totalDownload,
            'updated_at' => date('Y-m-d H:i:s'),
            'error' => false,
            'message' => '',
        ];

        if ($this->updateColumn($pages)) {
            //save on database juli
            $this->saveOnDatabase();
            return true;
        }

        return false;
    }

    public function updatePageTotal(int $id, int $totalPage)
    {
        $this->setFromLogs('Logs_Pages');
        $pages = [
            'page_total' => $totalPage,
            'totalPages' => $totalPage,
            'updated_at' => date('Y-m-d H:i:s'),
            'error' => false,
            'message' => '',
        ];

        if ($this->updateColumn($pages)) {
            //save on database juli
            $this->saveOnDatabase();
            return true;
        }

        return false;
    }

    public function updateError($message)
    {
        $this->setFromLogs('Logs_Pages');
        $pages = [
            'updated_at' => date('Y-m-d H:i:s'),
            'error' => true,
            'message' => $message,
        ];

        if ($this->updateColumn($pages)) {
            //save on database juli
            $this->saveOnDatabase();
            return true;
        }

        return false;
    }


    private function saveOnDatabase()
    {
        if (!file_exists(__DIR__ . '/config.php')) {
            return;
        }
        $this->setFromLogs('Logs_Pages');
        $this->select();
        $json = $this->getSelect()[0] ?? [];

        $config = include __DIR__ . '/config.php';
        $mysqli = mysqli_connect($config['hostname'], $config['user'], $config['password'], $config['database'])
            or die('No se pudo conectar: ' . mysqli_error());
        $sql = "SELECT * FROM jobs WHERE name ='scrapperInit' and status = 1 order by id DESC LIMIT 1";
        $id = null;
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $id = $row['id'];
            }
        }

        if (!is_null($id)) {
            $extraSQL = '';
            if ($json['termino'] && $json['end']) {
                $extraSQL = ' , status=2';
            }

            if ($json['error']) {
                $extraSQL = ' , status=3';
            }

            $message = json_encode($json);
            $sql = "UPDATE jobs SET modified=CONVERT_TZ(NOW(),'SYSTEM','UTC'), message= '" . $message . "'" . $extraSQL . " WHERE id=" . $id . ";";
            $result = $mysqli->query($sql);
        }
    }
}
