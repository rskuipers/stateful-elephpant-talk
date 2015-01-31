<?php

use Symfony\Component\HttpFoundation\RedirectResponse;

/** @var \Silex\Application $app */
$app['step.details'] = new \Application\Process\Checkout\DetailsStep('details', $app['twig']);
$app['step.payment'] = new \Application\Process\Checkout\PaymentStep('payment', $app['twig']);

$app->get('/', function (\Silex\Application $app) {
    return new RedirectResponse('/checkout/details');
})->bind('homepage');

$coordinator = new \Application\Process\Coordinator($app);

$coordinator->build(['details', 'payment']);

$app->get('/checkout/{stepName}', [$coordinator, 'display'])->bind('display');
$app->post('/checkout/{stepName}/forward', [$coordinator, 'forward'])->bind('forward');
