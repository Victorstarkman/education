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

        include_once $config;

        $this->token = new Token();
        $this->user = new User();
        $this->login = new Login();
        $this->logFailure = new Failure();
        $this->pageBot = new PageBot();
        $this->solicitudBot = new SolicitudBot();
        $this->Files = new SaveFile();
    }

    public function start(): void
    {
        try{
            echo "\n Start \n";
            $token = $this->token();

            echo "\r\n check if it has content to be processed \r\n";
            if ($this->checkIfItHasContentToBeProcessed()) {
                echo "\r\n scraping Solicitud \r\n";
                $this->solicitudBot->scrapingSolicitud($token);
                echo "\r\n restart scraping \r\n";
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
        echo "\r\n get token and get user \r\n";
        $dfataToken = $token = $this->token->getToken();
        $users = $this->user->getUser();

        if (empty($dfataToken)) {

            if (empty($users)) {
                $this->logFailure->prepareLog('There is no registered user or no user active', __FILE__, __LINE__);
                die;
            }
            echo "\n get token \n";
            $token = $this->login($users);
            echo "\n set token \n";
            $this->token->setToken($token);
            $idToken = $this->token->getToken()['id'];
        } else {
            echo "\r\n token exist \r\n";
            echo "\r\n set idToken \r\n";
            $idToken = $dfataToken['id'];
            echo "\r\n set token \r\n";
            $token = $dfataToken['token'];
            echo "\r\n check token \r\n";
            if (!checkToken($token)) {
                echo "\r\n delete token \r\n";
                $this->token->deleteToken($idToken);
                echo "\r\n get user \r\n";
                $token = $this->login($users);
                echo "\r\n set token \r\n";
                $this->token->setToken($token);
                echo "\r\n set idToken \r\n";
                $idToken = $this->token->getToken()['id'];
            }
        }

        echo "\r\n check token \r\n";
        if (!checkToken($token)) {
            echo "\r\n delete token \r\n";
            $this->token->deleteToken($idToken);
            echo "\r\n set log \r\n";
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
