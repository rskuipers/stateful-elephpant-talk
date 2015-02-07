<?php

namespace Application\Process\Checkout;

use Application\Process\Context;

class ReviewStep extends AbstractStep
{
    /**
     * {@inheritdoc}
     */
    public function display(Context $context)
    {
        return $this->render($context);
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

    /**
     * @param Context $context
     * @return string
     */
    protected function render(Context $context)
    {
        return $this->twig->render('checkout/review.html.twig', [
            'context' => $context,
            'order' => $this->getOrder(),
        ]);
    }
}
