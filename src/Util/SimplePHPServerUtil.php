<?php

namespace SimplePHPCasClient\Util;

/**
 * Class SimplePHPServerUtil
 * @package SimplePHPCasClient\Util
 */
class SimplePHPServerUtil
{
    /**
     * @param string $url
     * @param array $query
     * @return string
     */
    public static function buildQueryURL(string $url, array $query)
    {
        $query_str = '';
        foreach ($query as $k => $v) {
            $query_str .= $k . '=' . urlencode($v) . '&';
        }
        $query_str = rtrim($query_str, '&');
        return $url . '?' . $query_str;
    }
}