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
        return $this->render($context);
    }

    /**
     * {@inheritdoc}
     */
    public function forward(Context $context)
    {
        $request = $context->getRequest();
        if ($request->get('email')) {
            return true;
        }

        return $this->render($context);
    }

    /**
     * @param Context $context
     * @return string
     */
    protected function render(Context $context)
    {
        return $this->twig->render('checkout/details.html.twig', [
            'context' => $context
        ]);
    }
}
