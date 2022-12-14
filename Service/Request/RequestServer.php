<?php

namespace Service\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\SessionCookieJar;
use Matrix\Exception;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Cookie;

class RequestServer
{
    //Spider
    public static $user_agent = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:107.0) Gecko/20100101 Firefox/107.0';
    protected $cookies = [];
    public $client;

    //Spider
    public function __construct()
    {

        // Grava os cookies na SESSAO
        $this->cookies = new SessionCookieJar('SESSION_STORAGE', true);

        $this->client = new Client([
            'headers' => [
                'User-Agent' => self::$user_agent,
            ],
            'verify' => false,
            'cookies' => $this->cookies,
            'connect_timeout' => 30,
            'timeout' => 60
        ]);

    }
    public function setcookie($cookie)
    {

        $cookies = SetCookie::fromString($cookie);
        $cookies->setDomain('abc.gob.ar');
        $this->cookies->setCookie($cookies);



    }

    public function mycooky()
    {
        return $this->cookies;
    }

    public function request($url, $ref = null, $metodo = 'GET', $param = [])
    {
        try {
            preg_match('@(http[s]?:\/\/)?(.*?)\/@is', $url, $match);
            $host = $match[2];

            if (!is_null($ref)) {
                $a_param = array_replace_recursive([
                    'timeout' => 60,
                    'connect_timeout' => 30,
                    'verify' => false,
                    'track_redirects' => true,
                    'headers' => [
                        'Host' => $ref,
                        'User-Agent' => self::$user_agent,
                        'Referer' => $ref,
                    ], 'cookies' => $this->cookies
                ], $param);

            } else {
                $a_param = array_replace_recursive([
                    'timeout' => 60,
                    'connect_timeout' => 30,
                    'verify' => false,
                    'track_redirects' => true,
                    'headers' => [
                        'Host' => $host,
                        'User-Agent' => self::$user_agent,
                        'Referer' => $ref,
                    ], 'cookies' => $this->cookies
                ], $param);
            }


            try{
                $randoSleep = rand(1, 3);
                sleep($randoSleep);
                $res = $this->client->request($metodo, $url, $a_param);
            }catch (\Exception $e) {
                return sprintf("Excecao: %d - %s, acessando %s\n", $e->getCode(), $e->getMessage(), $url);
            }



            $code = $res->getStatusCode();

            if ($code < 400 && isset($res)) {


                return (string)$res->getBody();

            } elseif ($code == 0) {
                // return 0;
                return "Error: Connection Timeout, skipping that item to try again later...\n";
            } else {
                // return 1;
                return "Error code: $code";
            }
        } catch (Exception $e) {
            // return 2;
            return sprintf("Excecao: %d - %s, acessando %s\n", $e->getCode(), $e->getMessage(), $url);
        }
    }

    public function start()
    {

        echo $this->request('https://www.google.com/', 'https://www.google.com/', 'GET');
    }

    public function MeuIp()
    {
        $body = $this->request('https://www.meuip.com.br', 'https://www.meuip.com.br', 'GET', [
            'headers' => [
                'Host' => 'www.meuip.com.br'
            ]
        ]);
        if (preg_match('@meu\s+ip\s+.\s*(.*?)\s*<@is', $body, $m)) {
            echo $m[1];
        } else {
            echo $body;
        }
    }
}
