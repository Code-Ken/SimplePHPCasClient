<?php

use SimplePHPCasClient\Object\SimplePHPServerObject;
use SimplePHPCasClient\SimplePHPCasClient;

$ticket = ticket::getTicket();

$serverObject = new SimplePHPServerObject();
$serverObject->setServerHostName('set cas server url')
    ->setLocationService('set cas callback url');

$cas = new SimplePHPCasClient($serverObject);

if (empty($ticket)) {
    //not log in
    if ($_GET['ticket']) {
        if (!$cas->checkTicket()) $cas->locationLoginUrl();
        $userId = $cas->getUser();
        $userInfo = $cas->getAttributes();
        ticket::saveTicket($_GET['ticket']);

    } else {
        $cas->locationLoginUrl();
    }
}

if (!ticket::checkTicket($ticket)) $cas->locationLoginUrl();

$userInfo = ticket::getTicketInfo($ticket);
