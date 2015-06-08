<?php

namespace Application;

use Application\Model\Order;
use Silex\Application;
use SM\StateMachine\StateMachineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/** @var Application $app */

$app->get('/', function (Application $app) {
    $app['session']->set('order', new Order());
    return new RedirectResponse($app['url_generator']->generate('order'));
})->bind('homepage');

$order = $app['session']->get('order');

$app->get('/order', function (Application $app) use ($order) {
    /** @var StateMachineInterface $stateMachine */
    $stateMachine = $app['statemachine.factory']->get($order);
    return $app['twig']->render('order.html.twig', [
        'order' => $order,
        'stateMachine' => $stateMachine,
    ]);
})->bind('order');

$app->get('/order/{transition}', function (Application $app, $transition) use ($order) {
    /** @var StateMachineInterface $stateMachine */
    $stateMachine = $app['statemachine.factory']->get($order);
    $stateMachine->apply($transition);
    return new RedirectResponse($app['url_generator']->generate('order'));
})->bind('order/transition');
