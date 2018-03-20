<?php

namespace SimplePHPCasClient\Exception;

class SimplePHPCasException extends \RuntimeException
{
    public const CODE_ERROR = -1;
    public const CODE_UNEXPECT_ERROR = 44;
    public const CODE_HTTP_ERROR = 200;
    public const CODE_AUTH_ERROR = 401;
    public const CODE_PARAMS_ERROR = 400;
    public const CODE_STATUS_ERROR = 800;
    public const CODE_SQL_EXEC = 1000;
    public const CODE_SQL_SEARCH = 1001;
    public const CODE_INSERT_DUPLICATION = 1291;
}