<?php

namespace Service;

use Service\Bot\Login;
use Service\Bot\Index;
use Service\Bot\Solicitud;
use Service\LogService;
use Service\Treatment\TreatmentService;

require_once __DIR__ . '/../vendor/autoload.php';

class BotSinIndexador
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
        $this->Login = new Login(__DIR__);
        $this->Index = new Index(__DIR__);
        $this->Solicitud = new Solicitud(__DIR__);
        $this->LogService = new LogService(__DIR__);
        $this->TreatmentService = new TreatmentService(__DIR__);
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
        die('Bot has been stopped');
        return true;
    }

    private function createPaths(){
        if(!file_exists(__DIR__.'\\File')){
            mkdir(__DIR__.'\\File');
        }

        if(!file_exists(__DIR__.'\\File\\PageNoAprovadas')){
            mkdir(__DIR__.'\\File\\PageNoAprovadas');
        }

        if(!file_exists(__DIR__.'\\File\\Users')){
            mkdir(__DIR__.'\\File\\Users');
        }

        if(!file_exists(__DIR__.'\\File\\DatosTratados')){
            mkdir(__DIR__.'\\File\\DatosTratados');
        }
    }
}


$ts = new Bot();
$ts->run();