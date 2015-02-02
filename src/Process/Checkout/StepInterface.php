<?php

namespace Application\Process\Checkout;

use Application\Process\Context;

interface StepInterface
{
    /**
     * @param Context $context
     * @return mixed
     */
    public function display(Context $context);

    /**
     * @param Context $context
     * @return mixed
     */
    public function forward(Context $context);
}