<?php
require("../../vendor/autoload.php");
use SimplePHPCasClient\Object\SimplePHPServerObject;
use SimplePHPCasClient\SimplePHPCasClient;

//$ticket = ticket::getTicket();

$serverObject = new SimplePHPServerObject();
$serverObject->setServerHostName('set cas server url')
    ->setLocationService('set cas callback url');

$cas = new SimplePHPCasClient($serverObject);
$cas->setJWT('eyJhbGciOiJIUzUxMiJ9.ZXlKemRXSWlPaUl4SWl3aWMzVmpZMlZ6YzJaMWJFRjFkR2hsYm5ScFkyRjBhVzl1U0dGdVpHeGxjbk1pT2xzaVVtVnpkRUYxZEdobGJuUnBZMkYwYVc5dVNHRnVaR3hsY2lKZExDSjFjMlZ5VG04aU9pSXlNakFpTENKcGMzTWlPaUpvZEhSd2N6cGNMMXd2WTJGekxtVjRZVzF3YkdVdWIzSm5PamcwTkROY0wyTmhjeUlzSW5WelpYSk9ZVzFsSWpvaTZMYUY1N3FuNTY2aDU1Q0c1WkdZSWl3aVkzSmxaR1Z1ZEdsaGJGUjVjR1VpT2lKVmMyVnlibUZ0WlZCaGMzTjNiM0prUTNKbFpHVnVkR2xoYkNJc0ltRjFaQ0k2SW1oMGRIQnpPbHd2WEM5allYTXVaWGhoYlhCc1pTNXZjbWM2T0RRME0xd3ZZMkZ6SWl3aVlYVjBhR1Z1ZEdsallYUnBiMjVOWlhSb2IyUWlPaUpTWlhOMFFYVjBhR1Z1ZEdsallYUnBiMjVJWVc1a2JHVnlJaXdpZFhObGNrMXZZbWxzWlNJNklpczROakUzTmpBd05EVTNPRGc1SWl3aWRYTmxja1Z0WVdsc0lqb2llSGhBZUhndVkyOXRJaXdpWlhod0lqb3hOVEkwTmpjNE1qUXlMQ0pwWVhRaU9qRTFNalEyTkRrME5ESXNJbXAwYVNJNklsUkhWQzB5TFhKRWNYUkphQzFNYzFaNlYxSlZOMjkxWTFwaVRrMU1NSGxVVDJSclUwaHpUaTFPY21oNlUydFFTMVZYWTFOTFJGWlBObWN0UzIxMFdXaHJhR2t4U21oS2RIY3RhVnB0TldWaGFYY3paV2hvTXpFMFluQnVkbmt6WlZvaWZRPT0.Hmqgt_V91TJ7g90E8N-H--oIcynqDgGt19XpaK_rNfb6NX7tIsy2HkEMD9U0pyipMcpEfc_1Qf3Zw-seMoy6Xw');
//$cas->setJWT('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzb21lIjoicGF5bG9hZCJ9.4twFt5NiznN84AWoo1d7KO1T_yoc0Z6XOpOVswacPZg');
//$cas->setJWT('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzb21lIjoicGF5bG9hZCJ9.mvCzPRiUA4RYBdU6souFdgMZktdnoBakRXsSjGrU2no');
$cas->validJWT();die;


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
