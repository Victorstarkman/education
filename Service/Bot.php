<?php

namespace Service;

use Service\Bot\Login;
use Service\Bot\Index;
use Service\Bot\Solicitud;
use Service\LogService;
use Service\Treatment\TreatmentService;

require_once __DIR__ . '/../vendor/autoload.php';

class Bot
{
    private $attempt = 0;
    private $runNumber = 0;

    /**
     * @var Login $Login
     */
    private $Login;

    /**
     * @var Index $Index
     */
    private $Index;

    /**
     * @var Solicitud $Solicitud
     */
    private $Solicitud;

    /**
     * @var LogService $LogService
     */
    private $LogService;

    /**
     * @var TreatmentService $TreatmentService
     */
    private $TreatmentService;

    public function __construct()
    {
        $this->Login = new Login();
        $this->Index = new Index();
        $this->Solicitud = new Solicitud();
        $this->LogService = new LogService();
        $this->TreatmentService = new TreatmentService();
        $this->createPaths();
    }

    public function run()
    {
        $token = $this->Login->run();
        while(true) {

            if($this->attempt > 3){
                $log = [
                    'date' => date('Y-m-d H:i:s'),
                    'message' => 'the bot has been stopped because it has exceeded the number of attempts',
                ];
                $this->LogService->setLog($log, 'Failure', 'Bot.php');

                throw new \Exception('the bot has been stopped because it has exceeded the number of attempts');

                break;
            }


            if($this->runNumber >= 10){
                $log = [
                    'date' => date('Y-m-d H:i:s'),
                    'message' => 'the bot has been stopped because it has exceeded the number of executions',
                ];
                $this->LogService->setLog($log, 'Execution', 'Bot.php');
                break;
            }

            $log = [
                'date' => date('Y-m-d H:i:s'),
                'message' => 'starting the web crawler',
            ];
            $this->LogService->setLog($log, 'Execution', 'Bot.php');

            try{
                $this->Index->run($token);
                $this->TreatmentService->run();
                $this->Solicitud->run($token);

                if($this->attempt <> 0){
                    $this->attempt = 0;
                }

            }catch (\Exception $e){
                $log = [
                    'date' => date('Y-m-d H:i:s'),
                    'message' => $e->getMessage(),
                ];

                $this->LogService->setLog($log, 'Failure', 'Bot.php');
                $this->attempt++;
                $this->runNumber--;
            }

            $log = [
                'date' => date('Y-m-d H:i:s'),
                'message' => 'ending the web crawler',
            ];

            $this->runNumber++;

            $this->LogService->setLog($log, 'Execution', 'Bot.php');
        }
        return true;
    }

    private function createPaths(){
        if(!file_exists('File')){
            mkdir('File');
        }

        if(!file_exists('File/PageNoAprovadas')){
            mkdir('File/PageNoAprovadas');
        }

        if(!file_exists('File/Users')){
            mkdir('File/Users');
        }

        if(!file_exists('File/DatosTratados')){
            mkdir('File/DatosTratados');
        }
    }
}


$ts = new Bot();
$ts->run();
