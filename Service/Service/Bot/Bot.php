<?php

namespace Service\Bot;

require_once __DIR__ . '/../../vendor/autoload.php';
include_once __DIR__ . '/Helpes/Helpes.php';

use Repository\User\Token;
use Repository\User\User;
use Repository\Log\Failure;
use Service\Bot\components\Login;
use Service\Bot\components\PageBot;
use Service\Bot\components\SolicitudBot;
use Repository\File\SaveFile;
class Bot
{

    private $token;
    private $user;
    private $login;
    private $logFailure;
    private $pageBot;
    private $solicitudBot;
    private $Files;


    public function __construct()
    {
        //set environment in php 7.4
        $config = __DIR__ . '/Config/ConfigEnv.php';
        if(!file_exists($config))
            die('Config file Env not found');
        echo "set config file Env \r\n";
        include_once $config;

        echo "set repository token __construct  \r\n";
        $this->token = new Token();
        echo "set repository User __construct \r\n";
        $this->user = new User();
        echo "set Login  \r\n";
        $this->login = new Login();
        echo "set repository Failure __construct \r\n";
        $this->logFailure = new Failure();
        echo "set PageBot  __construct \r\n";
        $this->pageBot = new PageBot();
        echo "set  SolicitudBot __construct \r\n";
        $this->solicitudBot = new SolicitudBot();
        echo "set repository SaveFile  \r\n";
        $this->Files = new SaveFile();
    }

    public function start(): void
    {
        try{
            echo "\n Start \n";
            $token = $this->token();

            echo "check if it has content to be processed \r\n";
            if ($this->checkIfItHasContentToBeProcessed()) {
                echo "scraping Solicitud \r\n";
                $this->solicitudBot->scrapingSolicitud($token);
                echo "\restart scraping \r\n";
                $this->start();
                return;
            }

            $this->pageBot->scrapingPages($token);
            $this->solicitudBot->scrapingSolicitud($token);
            echo "\n Finish \n";
            die();
        }catch(\Exception $e){
            $this->logFailure->prepareLog($e->getMessage(), __FILE__, __LINE__);
            die($e->getMessage());
        }

    }

    private function token(): string
    {
        echo "get token and get user \r\n";
        $dfataToken = $token = $this->token->getToken();
        $users = $this->user->getUser();
        if (empty($dfataToken)) {

            if (empty($users)) {
                $this->logFailure->prepareLog('There is no registered user or no user active', __FILE__, __LINE__);
                die;
            }
            echo "get token \n";
            $token = $this->login($users);
            echo "set token \n";
            $this->token->setToken($token);
            $idToken = $this->token->getToken()['id'];
        } else {
            echo "token exist \r\n";
            echo "set idToken \r\n";
            $idToken = $dfataToken['id'];
            echo "set token \r\n";
            $token = $dfataToken['token'];
            echo "check token \r\n";
            if (!checkToken($token)) {
                echo "delete token \r\n";
                $this->token->deleteToken($idToken);
                echo "get user \r\n";
                $token = $this->login($users);
                echo "set token \r\n";
                $this->token->setToken($token);
                echo "set idToken \r\n";
                $idToken = $this->token->getToken()['id'];
            }
        }

        echo "check token \r\n";
        if (!checkToken($token)) {
            echo "delete token \r\n";
            $this->token->deleteToken($idToken);
            echo "set log \r\n";
            $this->logFailure->prepareLog('Token is invalid', __FILE__, __LINE__, $token);
            die;
        }

        return $token;
    }

    private function login(array $users): string
    {
        return $this->login->login($users['user'], $users['password']);
    }

    private function checkIfItHasContentToBeProcessed(): bool
    {
        return empty($this->Files->getPathPageDate()) ? false : true;
    }
}

$ts = new Bot();
$ts->start();
