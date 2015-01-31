<?php

namespace Application\Process\Checkout;

use Application\Process\Context;

class PaymentStep extends AbstractStep
{
    public function display(Context $context)
    {
        return $this->render($context);
    }

    public function forward(Context $context)
    {
        $request = $context->getRequest();

        if ($request->get('method')) {
            return true;
        }

        return $this->render($context);
    }

    protected function render(Context $context)
    {
        return $this->twig->render('checkout/payment.html.twig', [
            'context' => $context,
        ]);
    }
}
