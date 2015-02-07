<?php

namespace Application;

use SM\Factory\Factory as SMFactory;

$config = [
    'class'         => 'Application\Model\Order',
    'graph'         => 'default',
    'property_path' => 'state',
    'states'        => [
        'new',
        'pending',
        'completed',
        'canceled',
        'refunded',
    ],
    'transitions' => [
        'create' => [
            'from' => ['new'],
            'to'   => 'pending',
        ],
        'cancel' => [
            'from' => ['new', 'pending'],
            'to'   => 'canceled',
        ],
        'complete' => [
            'from' => ['pending'],
            'to'   => 'completed',
        ],
        'refund' => [
            'from' => ['completed'],
            'to'   => 'refunded',
        ],
    ],
];

$stateMachineFactory = new SMFactory([$config]);
$app['statemachine.factory'] = $stateMachineFactory;
