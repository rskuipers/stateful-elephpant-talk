<?php

use Application\Process\Coordinator;

/** @var $app \Silex\Application */

$coordinator = new Coordinator($app);
$coordinator
    ->setDisplayRoute('checkout/display')
    ->setForwardRoute('checkout/forward')
    ->setRedirectRoute('order')
    ->build([]);

$app->get('/checkout/start', [$coordinator, 'start'])->bind('checkout/start');
$app->get('/checkout/{stepName}', [$coordinator, 'display'])->bind('checkout/display');
$app->post('/checkout/{stepName}/forward', [$coordinator, 'forward'])->bind('checkout/forward');
