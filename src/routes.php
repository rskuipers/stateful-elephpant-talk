<?php

namespace Application;

use Application\Model\Order;
use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;

/** @var Application $app */

$app->get('/', function (Application $app) {
    $app['session']->set('order', new Order());
    return new RedirectResponse($app['url_generator']->generate('order'));
})->bind('homepage');

$order = $app['session']->get('order');

$app->get('/order', function (Application $app) use ($order) {
    return $app['twig']->render('order.html.twig', [
        'order' => $order,
    ]);
})->bind('order');
