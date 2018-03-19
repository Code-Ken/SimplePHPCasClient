<?php

namespace SimplePHPCasClient\Util;

class SimplePHPServerUtil
{
    public static function buildQueryURL(string $url, array $query): string
    {
        $query_str = '';
        foreach ($query as $k => $v) {
            $query_str .= $k . '=' . urlencode($v) . '&';
        }
        $query_str = rtrim($query_str, '&');
        return $url . '?' . $query_str;
    }
}