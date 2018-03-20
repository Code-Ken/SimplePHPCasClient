<?php

namespace SimplePHPCasClient\Object;

use SimplePHPCasClient\Exception\SimplePHPCasException;
use SimplePHPCasClient\Util\SimplePHPServerUtil;

class SimplePHPServerObject
{
    const CAS_VERSION_1_0 = 1.0;
    const CAS_VERSION_2_0 = 2.0;
    const CAS_VERSION_3_0 = 3.0;

    protected $serverHostName = '';
    protected $serverHostPort = 443;
    protected $serverHostURI = 'cas';
    protected $serverBaseURL = '';
    protected $serverLoginURI = 'login';
    protected $serverValidateURI = 'serviceValidate';
    protected $locationService = '';
    protected $formatter = 'JSON';

    private $serverLoginURL = '';
    private $serverValidateURL = '';
    private $casProtocolVersion = self::CAS_VERSION_3_0;


    public function getCASProtocolVersion(): float
    {
        return $this->casProtocolVersion;
    }

    public function setServerHostName(string $host_name): SimplePHPServerObject
    {
        $this->serverHostName = trim(trim(trim($host_name, '/'), 'https://'), 'http://');
        return $this;
    }

    public function getServerHostName(): string
    {
        return $this->serverHostName;
    }

    public function setServerPort(int $server_port): SimplePHPServerObject
    {
        $this->serverHostPort = $server_port;
        return $this;
    }


    public function getServerPort(): string
    {
        return $this->serverHostPort;
    }


    public function setServerURI(string $server_uri): SimplePHPServerObject
    {
        $this->serverHostURI = trim($server_uri, '/');
        return $this;
    }

    public function getServerURI(): string
    {
        return $this->serverHostURI;
    }


    public function getServerBaseURL(): string
    {
        $this->serverBaseURL = 'https://' . $this->serverHostName . ':' . $this->serverHostPort;
        if (!empty($this->serverHostURI)) $this->serverBaseURL .= '/' . $this->serverHostURI;
        return $this->serverBaseURL;
    }

    public function setServerLoginURI(string $login_uri): SimplePHPServerObject
    {
        $this->serverLoginURI = $login_uri;
        return $this;
    }

    public function getServerLoginURI()
    {
        return $this->serverLoginURI;
    }

    public function setServerValidateURI(string $validate_uri): SimplePHPServerObject
    {
        $this->serverValidateURI = $validate_uri;
        return $this;
    }

    public function getServerValidateURI()
    {
        return $this->serverValidateURI;
    }

    public function setLocationService(string $location_service): SimplePHPServerObject
    {
        $this->locationService = trim($location_service, '/');
        return $this;
    }

    public function getLocationService(): string
    {
        return $this->locationService;
    }

    public function getServerLoginURL(): string
    {
        $this->serverLoginURL = $this->getServerBaseURL() . '/' . $this->serverLoginURI;
        $query_arr = [
            'service' => $this->locationService,
        ];

        return SimplePHPServerUtil::buildQueryURL($this->serverLoginURL, $query_arr);
    }

    public function getServerValidateURL(): string
    {
        $this->serverValidateURL = $this->getServerBaseURL() . '/' . $this->serverValidateURI;
        $query_arr = [
            'ticket' => $_GET['ticket'],
            'service'=>$this->locationService,
            'format' => $this->formatter,
        ];
        return SimplePHPServerUtil::buildQueryURL($this->serverValidateURL, $query_arr);
    }

    public function check()
    {
        if (empty($this->serverHostName)) throw new SimplePHPCasException('请设置serverHostName', SimplePHPCasException::CODE_PARAMS_ERROR);
        if ($this->serverHostPort <= 0) throw new SimplePHPCasException('serverHostPort必须大于0', SimplePHPCasException::CODE_PARAMS_ERROR);
        if (empty($this->locationService)) throw new SimplePHPCasException('请设置locationService', SimplePHPCasException::CODE_PARAMS_ERROR);
    }
}
