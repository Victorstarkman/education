<?php
namespace Repository\Log;

use Repository\RepositoryBase;

class Failure extends RepositoryBase{



    public function __construct() {
        parent::__construct();
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
        $this->setFromLogs('Logs_Failure');
        $log = [
            'log' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ];

       return $this->insert($log);
    }
}
