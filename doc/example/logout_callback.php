<?php

use SimplePHPCasClient\SimplePHPCasClient;

$ticket = SimplePHPCasClient::getTicketFromLogoutXML();
ticket::invalidTicket($ticket);