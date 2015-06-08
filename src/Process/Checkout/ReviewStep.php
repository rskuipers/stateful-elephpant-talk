<?php

namespace Application\Process\Checkout;

use Application\Process\Context;
use SM\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig_Environment;

class ReviewStep extends AbstractStep
{
    /**
     * @var FactoryInterface
     */
    private $stateMachineFactory;

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
        parent::__construct($name, $twig, $session);
        $this->stateMachineFactory = $stateMachineFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function display(Context $context)
    {
        return $this->twig->render('checkout/review.html.twig', [
            'context' => $context,
            'order' => $this->getOrder(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function forward(Context $context)
    {
        $stateMachine = $this->stateMachineFactory->get($this->getOrder());
        $stateMachine->apply('create');
        return true;
    }
}
