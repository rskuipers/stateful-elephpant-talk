<?php

namespace Application\Process\Checkout;

use Application\Model\Order;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig_Environment;

abstract class AbstractStep implements Step
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
     * @param string $name
     * @param Twig_Environment $twig
     * @param SessionInterface $session
     */
    public function __construct(
        $name,
        Twig_Environment $twig,
        SessionInterface $session
    ) {
        $this->twig = $twig;
        $this->name = $name;
        $this->session = $session;
    }

    /**
     * @return string
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
