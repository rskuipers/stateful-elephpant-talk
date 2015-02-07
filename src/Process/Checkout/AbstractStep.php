<?php

namespace Application\Process\Checkout;

use Application\Model\Order;
use SM\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig_Environment;

abstract class AbstractStep implements StepInterface
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var FactoryInterface
     */
    protected $stateMachineFactory;

    /**
     * @param string $name
     * @param Twig_Environment $twig
     * @param SessionInterface $session
     * @param FactoryInterface $stateMachineFactory
     */
    public function __construct(
        $name,
        Twig_Environment $twig,
        SessionInterface $session,
        FactoryInterface $stateMachineFactory
    ) {
        $this->twig = $twig;
        $this->name = $name;
        $this->session = $session;
        $this->stateMachineFactory = $stateMachineFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Order
     */
    protected function getOrder()
    {
        return $this->session->get('order');
    }
}
