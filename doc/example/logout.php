<?php

use SimplePHPCasClient\Object\SimplePHPServerObject;
use SimplePHPCasClient\SimplePHPCasClient;

$serverObject = new SimplePHPServerObject();
$serverObject->setServerHostName('set cas server url')
    ->setLocationService('set cas callback url/logout_callback.php');

$cas = new SimplePHPCasClient($serverObject);
$cas->locationLogoutUrl();
