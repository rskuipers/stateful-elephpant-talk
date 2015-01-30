<?php

use Symfony\Component\HttpFoundation\RedirectResponse;

$app['step.details'] = new \Application\Process\Checkout\DetailsStep();

$app->get('/', function (\Silex\Application $app) {
    return new RedirectResponse('/checkout/details');
})->bind('homepage');

$coordinator = new \Application\Process\Coordinator($app);

$app->get('/checkout/{stepName}', [$coordinator, 'display']);
$app->post('/checkout/{stepName}/forward', [$coordinator, 'forward']);
