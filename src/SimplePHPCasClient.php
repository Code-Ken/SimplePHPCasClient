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

    /**
     * @var
     */
    public $jwt;

    /**
     * @var
     */
    public $bitSecret;

    /**
     * @var
     */
    public $payLoad;
    /**
     * @var
     */
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

    /**
     * @param string $jwt
     */
    public function setJWT(string $jwt): void
    {
        $this->jwt = $jwt;
    }

    /**
     * @param $secret
     */
    public function setBitSecret($secret): void
    {
        $this->bitSecret = $secret;
    }


    /**
     * @return bool
     */
    public function getPayLoad()
    {
        return $this->isValidJWT ? $this->payLoad : false;
    }

    /**
     * @return bool
     */
    public function validJWT(): bool
    {
        if (empty($this->jwt)) $this->setJWT(isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '');
        if (empty($this->jwt)) return $this->isValidJWT = false;
        $arr = explode('.', $this->jwt);
        if (count($arr) != 3) return $this->isValidJWT = false;
        $header = base64_decode($arr[0]);
        $payload = base64_decode($arr[1]);
        $signature = $arr[2];

        $header_arr = json_decode($header, 1);
        $data = JWT::urlsafeB64Encode($header) . '.' . JWT::urlsafeB64Encode($payload);
        $alg = JWT::$supported_algs[strtoupper($header_arr['alg'])];
        list($fun, $algo) = $alg;
        $string_bit = call_user_func($fun, $algo, $data, $this->bitSecret, true);
        $base64_str = base64_encode($string_bit);
        $gen_signature = str_replace('=', '', strtr($base64_str, '/+', '_-'));

        if ($this->isValidJWT = $gen_signature === $signature) $this->payLoad = json_decode(base64_decode($payload));
        return $this->isValidJWT;
    }

    /**
     * http 401 response
     */
    public function unAuthResponse()
    {
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }

}