<?php

namespace Application\Process\Checkout;

use Application\Process\Context;

class DetailsStep extends AbstractStep
{
    /**
     * {@inheritdoc}
     */
    public function display(Context $context)
    {
        return $this->twig->render('checkout/details.html.twig', [
            'context' => $context
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function forward(Context $context)
    {
        $request = $context->getRequest();

        if ($email = $request->get('email')) {
            $this->getOrder()->setEmail($email);
            return true;
        }

        return $this->display($context);
    }
}
