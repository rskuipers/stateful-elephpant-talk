<?php

namespace Application;

use Application\Model\Order;
use Application\Process\Checkout\DetailsStep;
use Application\Process\Checkout\PaymentStep;
use Application\Process\Checkout\ReviewStep;
use Application\Process\Coordinator;
use Silex\Application;

/** @var Application $app */

/** @var Order $order */
$order = $app['session']->get('order');

$coordinator = new Coordinator($app);
$coordinator->build([
        new DetailsStep('details', $app['twig'], $app['session']),
        new PaymentStep('payment', $app['twig'], $app['session']),
        new ReviewStep('review', $app['twig'], $app['session'], $app['statemachine.factory']),
    ])
    ->setForwardRoute('checkout/forward')
    ->setDisplayRoute('checkout/display')
    ->setRedirectRoute('order')
    ->setValidator(function () use ($order) {
        return $order->getState() === Order::STATE_NEW;
    });

$app->get('/checkout', [$coordinator, 'start'])->bind('checkout/start');
$app->get('/checkout/{stepName}', [$coordinator, 'display'])->bind('checkout/display');
$app->post('/checkout/{stepName}/forward', [$coordinator, 'forward'])->bind('checkout/forward');
