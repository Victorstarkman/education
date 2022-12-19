<?php

namespace Service;

use Service\Bot\Login;
use Service\Bot\Index;
use Service\LogService;

require_once __DIR__ . '/../vendor/autoload.php';

class Bot
{
    private $attempt = 0;

    /**
     * @var Login $Login
     */
    private $Login;

    /**
     * @var Index $Index
     */
    private $Index;

    /**
     * @var LogService $LogService
     */
    private $LogService;

    public function __construct()
    {
        $this->Login = new Login(__DIR__);
        $this->Index = new Index(__DIR__);
        $this->LogService = new LogService(__DIR__);

        $this->createPaths();
    }

    public function run()
    {
        $token = $this->Login->run();
        $this->setLogExeculte('starting the web crawler');

        while(true) {

            if($this->attempt > 3){
                $this->setLogFailue('the bot has been stopped because it has exceeded the number of attempts');

                throw new \Exception('the bot has been stopped because it has exceeded the number of attempts');

                break;
            }


            try{
                $this->Index->run($token);

                if($this->attempt <> 0){
                    $this->attempt = 0;
                }

            }catch (\Exception $e){
                $this->setLogFailue($e->getMessage());
                $this->attempt++;
            }

        }
        $this->setLogExeculte('ending the web crawler');

        die('Bot has been stopped');

        return true;
    }

    private function createPaths(){

        $path = [
            '/File',
            '/File/PageNoAprovadas',
            '/File/Users',
            '/File/DatosTratados',
        ];

        foreach ($path as $item){
            if(!file_exists(__DIR__.$item)){
                mkdir(__DIR__.$item);
            }
        }
    }

    private function setLogExeculte($message){
        $log = [
            'date' => date('Y-m-d H:i:s'),
            'message' => $message,
        ];
        $this->LogService->setLog($log, 'Execution', 'Bot.php');
    }

    private function setLogFailue($message) {
        $log = [
            'date' => date('Y-m-d H:i:s'),
            'message' => $message,
        ];

        $this->LogService->setLog($log, 'Failure', 'Bot.php');
    }
}


$ts = new Bot();
$ts->run();
