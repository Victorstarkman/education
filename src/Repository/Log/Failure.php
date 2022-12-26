<?php
namespace Repository\Log;

use sql\genericsqlformat\Insert\Insert;

class Failure {

    private $sqlInsert;

    public function __construct() {
        $this->sqlInsert = new Insert();
    }

    public function prepareLog(string $error, string $nameFile, int $line,  $data = null): bool {
        $log = [
            'error' => $error,
            'nameFile' => $nameFile,
            'line' => $line,
            'data' => $data
        ];

        return $this->setLog(json_encode($log));
    }

    private function setLog(string $message): bool {
        $this->sqlInsert->setFrom('log_failure');
        $this->sqlInsert->setDates(['log' => $message, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
        return $this->sqlInsert->run();
    }
}