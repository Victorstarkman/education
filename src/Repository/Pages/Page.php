<?php

namespace Repository\Pages;

use sql\genericsqlformat\Select\Select;
use sql\genericsqlformat\Insert\Insert;
use sql\genericsqlformat\Update\Update;

class Page
{
    private $sqlSelect;
    private $sqlInsert;
    private $sqlUpdate;

    public function __construct()
    {
        $this->sqlSelect = new Select();
        $this->sqlInsert = new Insert();
        $this->sqlUpdate = new Update();
    }

    public function getPage(): array
    {
        $this->sqlSelect->setFrom('pages');
        $this->sqlSelect->setColumns(['id', 'current_page', 'page_total', 'end', 'total_file', 'total_file_downloaded']);
        $this->sqlSelect->setLimit(1);
        $this->sqlSelect->setOrder(['id']);

        return $this->sqlSelect->run()[0] ?? [];
    }

    public function insertPage(int $pageTotal, int $currentPage, int $totalFileDownloaded, int $total_file): bool
    {
        $this->sqlInsert->setFrom('pages');
        $this->sqlInsert->setDates([
            'page_total' => $pageTotal,
            'current_page' => $currentPage,
            'total_file_downloaded' => $totalFileDownloaded,
            'total_file' => $total_file,
            'end' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->sqlInsert->run();
    }

    public function updateCurrentPage(int $id, int $currentPage, int $totalFileDownloaded): bool
    {
        $this->sqlUpdate->setFrom('pages');
        $this->sqlUpdate->setColumnsSet([
            'current_page' => $currentPage,
            'total_file_downloaded' => $totalFileDownloaded,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $this->sqlUpdate->setWhere(['id' => $id], ['id' => '=']);

        return $this->sqlUpdate->run();
    }

    public function updateEnd(int $id): bool
    {
        $this->sqlUpdate->setFrom('pages');
        $this->sqlUpdate->setColumnsSet([
            'end' => true,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $this->sqlUpdate->setWhere(['id' => $id], ['id' => '=']);

        return $this->sqlUpdate->run();
    }

    public function updateTotalPageAndTotalFiles(int $id, int $totalPage, int $totalFile): bool
    {
        $this->sqlUpdate->setFrom('pages');
        $this->sqlUpdate->setColumnsSet([
            'page_total' => $totalPage,
            'total_file' => $totalFile,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $this->sqlUpdate->setWhere(['id' => $id], ['id' => '=']);

        return $this->sqlUpdate->run();
    }

    public function updateFileDownload(int $id, int $totalDownload): bool{
        $this->sqlUpdate->setFrom('pages');
        $this->sqlUpdate->setColumnsSet([
            'total_file_downloaded' => $totalDownload,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $this->sqlUpdate->setWhere(['id' => $id], ['id' => '=']);

        return $this->sqlUpdate->run();
    }

    public function updatePages(int $id, int $totalPage, int $totalFile, int $totalDownload): bool{
        $this->sqlUpdate->setFrom('pages');
        $this->sqlUpdate->setColumnsSet([
            'page_total' => $totalPage,
            'current_page' => $totalPage,
            'total_file' => $totalFile,
            'total_file_downloaded' => $totalDownload,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $this->sqlUpdate->setWhere(['id' => $id], ['id' => '=']);

        return $this->sqlUpdate->run();
    }

    public function updatePageTotal(int $id, int $totalPage) {
        $this->sqlUpdate->setFrom('pages');
        $this->sqlUpdate->setColumnsSet([
            'page_total' => $totalPage,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $this->sqlUpdate->setWhere(['id' => $id], ['id' => '=']);

        return $this->sqlUpdate->run();
    }

}
