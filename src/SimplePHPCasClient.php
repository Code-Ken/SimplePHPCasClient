<?php

namespace SimplePHPCasClient;

use Curl\Curl;
use SimplePHPCasClient\Object\SimplePHPServerObject;
use SimplePHPCasClient\Exception\SimplePHPCasException;


/**
 * Class SimplePHPCasClient
 * @package SimplePHPCasClient
 * @author gaosong0301@foxmail.com
 */
class SimplePHPCasClient
{

    /**
     * @var SimplePHPServerObject
     */
    public $serverObject;

    /**
     * @var
     */
    private $user;
    /**
     * @var
     */
    private $attributes;

    /**
     * @var string
     */
    private $failMsg = '';

    /**
     * SimplePHPCasClient constructor.
     * @param SimplePHPServerObject $serverObject
     */
    public function __construct(SimplePHPServerObject $serverObject)
    {
        $this->serverObject = $serverObject;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function getLocationLoginUrl(): string
    {
        return $this->serverObject->getServerLoginURL();
    }

    /**
     *Location to login page
     */
    public function locationLoginUrl()
    {
        $login_url = $this->getLocationLoginUrl();
        header('Location:' . $login_url);
        exit;
    }

    /**
     * @return string
     */
    public function getLocationLogoutUrl()
    {
        return $this->serverObject->getServerLogoutURL();
    }

    /**
     *Location to logout page
     */
    public function locationLogoutUrl()
    {
        $login_url = $this->getLocationLogoutUrl();
        header('Location:' . $login_url);
        exit;
    }

    /**
     * @return bool
     * @throws SimplePHPCasException
     */
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