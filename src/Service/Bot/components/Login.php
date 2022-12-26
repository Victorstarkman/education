<?php 
namespace Service\Bot\components;

use Service\Request\RequestServer;

class Login extends RequestServer
{
    private const DEFAULT_HEADER = [
        "Host" => "login.abc.gob.ar",
        "User-Agent" => "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:107.0) Gecko/20100101 Firefox/107.0",
        "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8",
        "Accept-Language" => "es-AR;q=0.8,en-US;q=0.5,en;q=0.3",
        "Accept-Encoding" => "gzip, deflate, br",
        "Content-Type" => "application/x-www-form-urlencoded",
        "Origin" => "https://login.abc.gob.ar",
        "Connection" => "keep-alive",
        "Referer" => "https://login.abc.gob.ar/nidp/idff/sso?id=ABC-Form&sid=1&option=credential&sid=1&target=https://menu.abc.gob.ar",
        "Upgrade-Insecure-Requests" => "1",
        "Sec-Fetch-Dest" => "document",
        "Sec-Fetch-Mode" => "navigate",
        "Sec-Fetch-Site" => "same-origin",
        "Sec-Fetch-User" => "?1"
    ];

    private $APP_VERSION;

    /**
     * @var RequestServer $Request
     */
    private $Request;

    public function __construct()
    {
        parent::__construct();
        $this->Request = new RequestServer();
        $this->APP_VERSION = getenv('APP_VERSION', '1.4.4');
    }

    public function login($user, $pass)
    {
        $this->requestGetLogin();
        $this->requestPostLogin($user, $pass);
        $this->requestGetMisLicencias();
        $this->requestGetLoginCallback();
        $this->requestGetUser();
        $this->requestGetMisLicencias();
        $TokenSAML = $this->requestGetLicenciasMedicasWeb();
        $this->requestPostLicenciasMedicasWebCooky($TokenSAML);
        $body = $this->requestPostLicenciasMedicasWebAuth();
        $token = $this->checkIfItHasToken($body);
        return $token;
    }

    private function requestGetLogin(): void
    {
        $this->Request->request('https://login.abc.gob.ar', 'https://login.abc.gob.ar', 'GET', [
            'headers' => self::DEFAULT_HEADER
        ], true);
    }

    private function requestPostLogin(string $user, string $pass)
    {
        $payLoad = [
           'form_params' => [
                'option' => 'credential',
                'Ecom_User_ID' => $user,
                'Ecom_Password' => $pass
           ],
           'headers' => self::DEFAULT_HEADER
        ];

        return $this->Request->request('https://login.abc.gob.ar/nidp/idff/sso?id=ABC-Form&sid=1&option=credential&sid=1&target=https://menu.abc.gob.ar/','https://login.abc.gob.ar/nidp/portal','POST',$payLoad,false);

    }

    private function requestGetMisLicencias()
    {
        return $this->Request->request('https://menu.abc.gob.ar/api/services/link/Mis Licencias','https://menu.abc.gob.ar','GET',[
            'headers' => [
                'Host' => 'menu.abc.gob.ar',
                'Origin' => 'https://menu.abc.gob.ar',
            ]
        ],false);
    }

    private function requestGetLicenciasMedicasWeb()
    {
        $body = $this->Request->request('https://misaplicaciones5.abc.gob.ar/LicenciasMedicasWeb','https://menu.abc.gob.ar','GET',[
            'headers' => [
                'Host' => 'misaplicaciones5.abc.gob.ar',
                'Origin' => 'https://misaplicaciones5.abc.gob.ar',
            ]
        ],false);

        $body = $this->Request->request('https://misaplicaciones5.abc.gob.ar/LicenciasMedicasWeb/','https://menu.abc.gob.ar','GET',[
            'headers' => [
                'Host' => 'misaplicaciones5.abc.gob.ar',
                'Origin' => 'https://misaplicaciones5.abc.gob.ar',
            ]
        ],false);

        $TokenSAML = $this->getTokenSAMLResponse($body);

        if ($TokenSAML) {

            return $TokenSAML;
        }

        $this->LogService->setLog([
            'message' => 'No se puede obtener el token de respuesta SAML',
            'function' => 'requestGetLicenciasMedicasWeb',
            'body' => $body
        ], 'Failure', 'Login');

        throw new \Exception('No se puede obtener el token de respuesta SAML ');
    }

    private function requestPostLicenciasMedicasWebCooky($token)
    {
        $body = $this->Request->request('https://misaplicaciones5.abc.gob.ar/LicenciasMedicasWeb/servlet/auth/samlresponse','https://misaplicaciones5.abc.gob.ar/LicenciasMedicasWeb','POST',[
            'form_params' => [
                'SAMLResponse' => $token
            ],
            'headers' => [
                "Host" => "misaplicaciones5.abc.gob.ar",
                "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8",
                "Accept-Language" => "es-AR;q=0.8,en-US;q=0.5,en;q=0.3",
                "Accept-Encoding" => "gzip, deflate, br",
                "Content-Type" => "application/x-www-form-urlencoded",
                "Origin" => "https://login.abc.gob.ar",
                "Connection" => "keep-alive",
                "Referer" => "https://login.abc.gob.ar/",
                "Upgrade-Insecure-Requests" => "1",
                "Sec-Fetch-Dest" => "document",
                "Sec-Fetch-Mode" => "navigate",
                "Sec-Fetch-Site" => "same-site"
            ]
            ], false) ;


        return $body;
    }

    private function requestPostLicenciasMedicasWebAuth()
    {
        return $this->Request->request('https://misaplicaciones5.abc.gob.ar/LicenciasMedicasWeb/servlet/session/auth','https://misaplicaciones5.abc.gob.ar/LicenciasMedicasWeb/index.html', 'POST',[
            'headers' => [
                "Host" => "misaplicaciones5.abc.gob.ar",
                'Content-Type' => 'application/json',
                'ar.gob.abc.slm.perfil' => 'PRESTADORA',
                'app-version' => $this->APP_VERSION,
            ]
            ], false) ;
    }

    private function requestGetLoginCallback()
    {
       $body =  $this->Request->request('https://menu.abc.gob.ar/api/services','https://menu.abc.gob.ar','GET',[
            'headers' => [
                'Host' => 'menu.abc.gob.ar',
                'Accept' => 'application/json, text/plain, */*',
                'DNT' => '1',
            ]
        ], false);

        if (
            preg_match('@enctype=[\'"]application/x-www-form-urlencoded[\'"]\s*action=[\'"](.*?)[\'"]@is', $body,$url) &&
            preg_match('@name=[\'|"]SAMLResponse[\'|"]\s*value=[\'|"](.*?)[\'|"]@is', $body,$token)
            ){

            $body = $this->Request->request($url[1],'https://menu.abc.gob.ar/api/services','POST',[
                'headers' => [
                    'Host' => 'menu.abc.gob.ar',
                    'Accept' => 'application/json, text/plain, */*',
                    'DNT' => '1',
                ],
                'form_params' => [
                    'SAMLResponse' => $token[1]
                ]
            ], false);
            return;
        }

        $this->LogService->setLog([
            'message' => 'No se pudo realizar la devolución de llamada',
            'function' => 'requestGetLoginCallback',
            'body' => $body
        ], 'Failure', 'Login');

        throw new \Exception('No se pudo realizar la devolución de llamada ');
    }

    private function requestGetUser()
    {
        $body = $this->Request->request('https://menu.abc.gob.ar/api/user','https://menu.abc.gob.ar','GET',[
            'headers' => [
                'Host' => 'menu.abc.gob.ar',
                'Accept' => 'application/json, text/plain, */*',
                'DNT' => '1',
            ]
        ], true);

        $json = json_decode($body);

        if($json->type == "PRESTADORA"){
           return true;
        }

        $this->LogService->setLog([
            'message' => 'No se pudo obtener el usuario o el usuario no es un proveedor',
            'function' => 'requestGetUser',
            'body' => $body
        ], 'Failure', 'Login');

        throw new \Exception('No se pudo obtener el usuario o el usuario no es un proveedor ');
    }

    private function getTokenSAMLResponse($body)
    {
        $regex = '@name=[\'|"]SAMLResponse[\'|"]\s*value=[\'|"](.*?)[\'|"]@is';

        if(preg_match_all($regex, $body, $matches)){
           return $matches[1][0];
        }

        $this->LogService->setLog([
            'message' => 'No se pudo obtener el SAMLResponse',
            'function' => 'getTokenSAMLResponse',
            'body' => $body
        ], 'Failure', 'Login');

        throw new \Exception('No se pudo obtener el SAMLResponse');
    }

    private function checkIfItHasToken($body=null)
    {
        try{
            $json = json_decode($body);

            if (empty($json) || !isset($json->token)) {
                $this->LogService->setLog([
                    'message' => 'No se pudo obtener el user Token',
                    'function' => 'checkIfItHasToken',
                    'body' => $body
                ], 'Failure', 'Login');

                throw new \Exception('No se pudo obtener el user Token');
            }

            return $json->token ;
        }catch(\Exception $e){
            $this->LogService->setLog([
                'message' => 'No se pudo obtener el user Token',
                'function' => 'checkIfItHasToken',
                'body' => $body,
                'exception' => $e->getMessage()
            ], 'Failure', 'Login');

            throw new \Exception('No se pudo obtener el user Token');
        }
    }
}