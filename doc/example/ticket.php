<?php

use  SimplePHPCasClient\CasInterface\TicketInterface;

class ticket implements TicketInterface
{
    public static function getTicket(): string
    {
        $ticket = '';
        //case 1 we can get ticket from cookie
        //$ticket = $_COOKIE['ticket'];

        //case 2 we can get ticket from header
        //$ticket = $header['auth'];

        //case 3 we can get ticket from jwt
        //$ticket = $jwt['']['ticket'];

        //.....

        $ticket = 'balabala';
        return $ticket;
    }

    public static function getTicketInfo(string $ticket): array
    {
        $ticket_info = [];
        //case 1 redis/memcache
        //$ticket_info = ....

        //case 2 mysql/sqlite
        //$ticket_info = ....

        //case 3 file
        //$ticket_info = ....

        return $ticket_info;
    }

    public static function saveTicket(string $ticket, array ...$arr): bool
    {
        //case 1 redis/memcache
        //$ticket_info = ....

        //case 2 mysql/sqlite
        //$ticket_info = ....

        //case 3 file
        //$ticket_info = ....

        return true;
    }

    public static function invalidTicket(string $ticket): bool
    {
        return true;
    }

    public static function checkTicket(string $ticket): bool
    {
        return true;
    }
}