<?php

namespace SimplePHPCasClient\Object;

use SimplePHPCasClient\Exception\SimplePHPCasException;
use SimplePHPCasClient\Util\SimplePHPServerUtil;

/**
 * Class SimplePHPServerObject
 * @package SimplePHPCasClient\Object
 * @author gaosong0301@foxmail.com
 */
class SimplePHPServerObject
{
    /**
     * const
     */
    const CAS_VERSION_1_0 = 1.0;
    /**
     * const
     */
    const CAS_VERSION_2_0 = 2.0;
    /**
     * const
     */
    const CAS_VERSION_3_0 = 3.0;

    /**
     * @var string
     */
    protected $serverHostName = '';
    /**
     * @var int
     */
    protected $serverHostPort = 443;
    /**
     * @var string
     */
    protected $serverHostURI = 'cas';
    /**
     * @var string
     */
    protected $serverBaseURL = '';
    /**
     * @var string
     */
    protected $serverLoginURI = 'login';
    /**
     * @var string
     */
    protected $serverLogoutURI = 'logout';
    /**
     * @var string
     */
    protected $serverValidateURI = 'serviceValidate';

    /**
     * @var string
     */
    protected $serverValidateJwtURI = 'jwt/check';
    /**
     * @var string
     */
    protected $locationService = '';
    /**
     * @var string
     */
    protected $formatter = 'JSON';

    /**
     * @var string
     */
    private $serverLoginURL = '';
    /**
     * @var string
     */
    private $serverValidateURL = '';

    /**
     * @var string
     */
    private $serverLogoutURL = '';
    /**
     * @var float
     */
    private $casProtocolVersion = self::CAS_VERSION_3_0;

    /**
     * @var
     */
    private $ticket;

    /**
     * @return float
     */
    public function getCASProtocolVersion(): float
    {
        return $this->casProtocolVersion;
    }

    /**
     * @param string $host_name
     * @return SimplePHPServerObject
     */
    public function setServerHostName(string $host_name): SimplePHPServerObject
    {
        $this->serverHostName = ltrim(ltrim(rtrim($host_name, '/'), 'https://'), 'http://');
        return $this;
    }

    /**
     * @return string
     */
    public function getServerHostName(): string
    {
        return $this->serverHostName;
    }

    /**
     * @param int $server_port
     * @return SimplePHPServerObject
     */
    public function setServerPort(int $server_port): SimplePHPServerObject
    {
        $this->serverHostPort = $server_port;
        return $this;
    }


    /**
     * @return string
     */
    public function getServerPort(): string
    {
        return $this->serverHostPort;
    }


    /**
     * @param string $server_uri
     * @return SimplePHPServerObject
     */
    public function setServerURI(string $server_uri): SimplePHPServerObject
    {
        $this->serverHostURI = trim($server_uri, '/');
        return $this;
    }

    /**
     * @return string
     */
    public function getServerURI(): string
    {
        return $this->serverHostURI;
    }


    /**
     * @return string
     */
    public function getServerBaseURL(): string
    {
        $this->serverBaseURL = 'https://' . $this->serverHostName . ':' . $this->serverHostPort;
        if (!empty($this->serverHostURI)) $this->serverBaseURL .= '/' . $this->serverHostURI;
        return $this->serverBaseURL;
    }

    /**
     * @param string $login_uri
     * @return SimplePHPServerObject
     */
    public function setServerLoginURI(string $login_uri): SimplePHPServerObject
    {
        $this->serverLoginURI = $login_uri;
        return $this;
    }

    /**
     * @return string
     */
    public function getServerLoginURI(): string
    {
        return $this->serverLoginURI;
    }

    /**
     * @param string $logout_uri
     * @return SimplePHPServerObject
     */
    public function setServerLogoutURI(string $logout_uri): SimplePHPServerObject
    {
        $this->serverLogoutURI = $logout_uri;
        return $this;
    }

    /**
     * @return string
     */
    public function getServerLogoutURI(): string
    {
        return $this->serverLogoutURI;
    }

    /**
     * @param string $validate_uri
     * @return SimplePHPServerObject
     */
    public function setServerValidateURI(string $validate_uri): SimplePHPServerObject
    {
        $this->serverValidateURI = $validate_uri;
        return $this;
    }

    /**
     * @return string
     */
    public function getServerValidateURI()
    {
        return $this->serverValidateURI;
    }

    /**
     * @param string $validate_jwt_uri
     * @return SimplePHPServerObject
     */
    public function setServerValidateJwtURI(string $validate_jwt_uri): SimplePHPServerObject
    {
        $this->serverValidateJwtURI = $validate_jwt_uri;
        return $this;
    }

    /**
     * @return string
     */
    public function getServerValidateJwtURI()
    {
        return $this->serverValidateJwtURI;
    }

    /**
     * @param string $location_service
     * @return SimplePHPServerObject
     */
    public function setLocationService(string $location_service): SimplePHPServerObject
    {
        $this->locationService = trim($location_service, '/');
        return $this;
    }

    /**
     * @return string
     */
    public function getLocationService(): string
    {
        return $this->locationService;
    }

    /**
     * @return string
     */
    public function getServerLoginURL(): string
    {
        $this->serverLoginURL = $this->getServerBaseURL() . '/' . $this->serverLoginURI;
        $query_arr = [
            'service' => $this->locationService,
        ];

        return SimplePHPServerUtil::buildQueryURL($this->serverLoginURL, $query_arr);
    }

    /**
     * @return string
     */
    public function getServerLogoutURL(): string
    {
        $this->serverLogoutURL = $this->getServerBaseURL() . '/' . $this->serverLogoutURI;

        $query_arr = [];
        if (!empty($this->locationService)) $query_arr['service'] = $this->locationService;
        return SimplePHPServerUtil::buildQueryURL($this->serverLogoutURL, $query_arr);
    }


    /**
     * @return string
     */
    public function getServerValidateURL(): string
    {
        $this->serverValidateURL = $this->getServerBaseURL() . '/' . $this->getServerValidateURI();
        $query_arr = [
            'ticket'  => $this->getTicket(),
            'service' => $this->locationService,
            'format'  => $this->formatter,
        ];
        return SimplePHPServerUtil::buildQueryURL($this->serverValidateURL, $query_arr);
    }

    /**
     * @return string
     */
    public function getServerValidateJwtURL(): string
    {
        $this->serverValidateURL = $this->getServerBaseURL() . '/' . $this->getServerValidateJwtURI();
        $query_arr = [
            'jwt' => $this->getTicket(),
        ];
        return SimplePHPServerUtil::buildQueryURL($this->serverValidateURL, $query_arr);
    }


    /**
     * @throws SimplePHPCasException
     */
    public function check()
    {
        if (empty($this->serverHostName)) throw new SimplePHPCasException('请设置serverHostName', SimplePHPCasException::CODE_PARAMS_ERROR);
        if ($this->serverHostPort <= 0) throw new SimplePHPCasException('serverHostPort必须大于0', SimplePHPCasException::CODE_PARAMS_ERROR);
        if (empty($this->locationService)) throw new SimplePHPCasException('请设置locationService', SimplePHPCasException::CODE_PARAMS_ERROR);
    }


    /**
     * @return mixed
     */
    public function getTicket()
    {
        if (!empty($this->ticket)) return $this->ticket;
        if (!empty($_GET['ticket'])) return $_GET['ticket'];
        if (!empty($_POST['ticket'])) return $_POST['ticket'];
        if (!empty($_SERVER['ticket'])) return $_SERVER['ticket'];
        if (!empty($_REQUEST['ticket'])) return $_REQUEST['ticket'];
        return '';
    }

    /**
     * @param $ticket
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;
    }
}
