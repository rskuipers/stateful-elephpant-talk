<?php

namespace Application;

use Application\Model\Order;
use Application\Process\Checkout\DetailsStep;
use Application\Process\Checkout\PaymentStep;
use Application\Process\Checkout\ReviewStep;
use Application\Process\Coordinator;
use Silex\Application;

/** @var Application $app */
$app['step.details'] = new DetailsStep('details', $app['twig'], $app['session'], $app['statemachine.factory']);
$app['step.payment'] = new PaymentStep('payment', $app['twig'], $app['session'], $app['statemachine.factory']);
$app['step.review'] = new ReviewStep('review', $app['twig'], $app['session'], $app['statemachine.factory']);

/** @var Order $order */
$order = $app['session']->get('order');

$coordinator = new Coordinator($app);
$coordinator->build(['details', 'payment', 'review'])
    ->setForwardRoute('checkout/forward')
    ->setDisplayRoute('checkout/display')
    ->setRedirectRoute('order')
    ->setValidator(function () use ($order) {
        return $order->getState() === Order::STATE_NEW;
    });

$app->get('/checkout', [$coordinator, 'start'])->bind('checkout/start');
$app->get('/checkout/{stepName}', [$coordinator, 'display'])->bind('checkout/display');
$app->post('/checkout/{stepName}/forward', [$coordinator, 'forward'])->bind('checkout/forward');
