<?php

use Application\Model\Order;

return [
    'class'         => Order::class,
    'graph'         => 'default',
    'property_path' => 'state',
    'states'        => [
        Order::STATE_NEW,
        Order::STATE_PENDING,
        Order::STATE_COMPLETED,
        Order::STATE_CANCELED,
        Order::STATE_REFUNDED,
    ],
    'transitions' => [
        'create' => [
            'from' => [Order::STATE_NEW],
            'to'   => Order::STATE_PENDING,
        ],
        'cancel' => [
            'from' => [Order::STATE_NEW, Order::STATE_PENDING],
            'to'   => Order::STATE_CANCELED,
        ],
        'complete' => [
            'from' => [Order::STATE_PENDING],
            'to'   => Order::STATE_COMPLETED,
        ],
        'refund' => [
            'from' => [Order::STATE_COMPLETED],
            'to'   => Order::STATE_REFUNDED,
        ],
    ],
];
