<?php

use Application\Process\Checkout;

/** @var $app \Silex\Application */

$checkout = new Checkout($app);
$checkout
    ->setDisplayRoute('checkout/display')
    ->setForwardRoute('checkout/forward')
    ->setRedirectRoute('order')
    ->build([
        new Checkout\Details('details', $app['twig'], $app['session'])
    ]);

$app->get('/checkout/start', [$checkout, 'start'])->bind('checkout/start');
$app->get('/checkout/{stepName}', [$checkout, 'display'])->bind('checkout/display');
$app->post('/checkout/{stepName}/forward', [$checkout, 'forward'])->bind('checkout/forward');
