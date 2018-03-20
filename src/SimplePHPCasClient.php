<?php

namespace SimplePHPCasClient;

use Curl\Curl;
use SimplePHPCasClient\Object\SimplePHPServerObject;

class SimplePHPCasClient
{

    public $serverObject;

    private $user;
    private $attributes;

    private $failMsg = '';

    public function __construct(SimplePHPServerObject $serverObject)
    {
        $this->serverObject = $serverObject;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getLocationLoginUrl(): string
    {
        return $this->serverObject->getServerLoginURL();
    }

    public function locationLoginUrl()
    {
        $login_url = $this->getLocationLoginUrl();
        header('Location:' . $login_url);
        exit;
    }

    public function checkTicket()
    {
        $validate_url = $this->serverObject->getServerValidateURL();
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get($validate_url);

        if ($curl->curlErrorCode != 0) throw new SimplePHPCasException($curl->curlErrorMessage, SimplePHPCasException::CODE_HTTP_ERROR);
        $response_arr = json_decode($curl->rawResponse, true);


        if (!isset($response_arr['serviceResponse'])) throw new SimplePHPCasException('检验ticket失败', SimplePHPCasException::CODE_AUTH_ERROR);
        if (isset($response_arr['serviceResponse']['authenticationFailure'])) {
            $this->failMsg = $response_arr['serviceResponse']['authenticationFailure']['description'];
            return false;
        }

        $content = $response_arr['serviceResponse']['authenticationSuccess'];

        $this->user = $content['user'];
        $this->attributes = $content['attributes'];
        return true;
    }

}