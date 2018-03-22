<?php

namespace SimplePHPCasClient\CasInterface;

/**
 * Interface TicketInterface
 * @package SimplePHPCasClient\CasInterface
 */
interface TicketInterface
{
    /**
     * We need to invalid Ticket when we get cas service callback
     * @param string $ticket
     * @return bool
     */
    public static function invalidTicket(string $ticket): bool;

    /**
     * We need to save the ticket as unique id when we login success;
     * @param string $ticket
     * @return bool
     */
    public static function saveTicket(string $ticket): bool;

    /**
     * We need to check the ticket is valid When we get ticket form frontend
     * @param string $ticket
     * @return bool
     */
    public static function checkTicket(string $ticket): bool;

    /**
     * We can get user info from ticket
     * @param string $ticket
     * @return array
     */
    public static function getTicketInfo(string $ticket): array;

    /**
     * @return string
     */
    public static function getTicket(): string;

}
