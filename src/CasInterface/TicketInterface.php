<?php

namespace SimplePHPCasClient\CasInterface;

/**
 * Interface TicketInterface
 * @package SimplePHPCasClient\CasInterface
 */
interface TicketInterface
{
    /**
     * @param string $ticket
     * @return bool
     */
    public function invalidTicket(string $ticket): bool;

    /**
     * @param string $ticket
     * @return bool
     */
    public function saveTicket(string $ticket): bool;

    /**
     * @param string $ticket
     * @return bool
     */
    public function checkTicket(string $ticket): bool;

    /**
     * @param string $ticket
     * @return array
     */
    public function getTicket(string $ticket): array;
}
