<?php

namespace SimplePHPCasClient;

use Curl\Curl;
use SimplePHPCasClient\Object\SimplePHPServerObject;
use SimplePHPCasClient\Exception\SimplePHPCasException;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;


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

    public $jwt;

    public $isValidJWT;
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

    /**
     * @param string $xml
     * @return string
     */
    public static function getTicketFromLogoutXML(string $xml = ''): string
    {
        if (empty($xml)) $xml = $_REQUEST['logoutRequest'];

        preg_match('/(?<=\<samlp\:SessionIndex\>).*?(?=\<\/samlp\:SessionIndex\>)/', $xml, $arr);
        $ticket = current($arr);
        if (empty($ticket)) throw new SimplePHPCasException('获取ticket失败!', SimplePHPCasException::CODE_PARAMS_ERROR);
        return $ticket;
    }

    public function setJWT(string $jwt): void
    {
        $this->jwt = $jwt;
    }

    public function validJWT()
    {
        if (empty($this->jwt)) $this->setJWT(isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '');
        if (empty($this->jwt)) return $this->isValidJWT = false;
        $arr = explode('.', $this->jwt);
        if (count($arr) != 3) return $this->isValidJWT = false;
        $header = base64_decode($arr[0]);
        var_dump($header);
        $payload = base64_decode($arr[1]);
        $signature = $arr[2];


        var_dump($header);
        var_dump($payload);
        $n = '5y_tcYAjE9FmNcnpVIPfHlqL4nRKF_ZlFTL5x_QcVe4vmFXOe5CsFdqxt0lgBDfn1Y-6aISgzBAtvI9PRFZmnA';
        var_dump(base64_decode($n));
        $b = JWT::urlsafeB64Encode($header) . '.' . JWT::urlsafeB64Encode($payload);
        $m = hash_hmac('sha512', $b, $n, true);
        $m = base64_encode($m);
        $m = strtr($m, '/+', '_-');
        var_dump(str_replace('=', '', $m));
        var_dump($signature);
    }


    private function verifySign()
    {

    }



}