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
        include_once __DIR__ . '/Config/ConfigEnv.php';

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
            $token = $this->token();

            if ($this->checkIfItHasContentToBeProcessed()) {
                $this->solicitudBot->scrapingSolicitud($token);
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
            $idToken = $dfataToken['id'];
            $token = $dfataToken['token'];

            if (!checkToken($token)) {
                $this->token->deleteToken($idToken);
                $token = $this->login($users);
                $this->token->setToken($token);
                $idToken = $this->token->getToken()['id'];
            }
        }

        if (!checkToken($token)) {
            $this->token->deleteToken($idToken);
            $this->logFailure->prepareLog('Token is invalid', __FILE__, __LINE__);
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
