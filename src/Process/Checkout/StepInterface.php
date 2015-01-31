<?php

namespace Application\Process\Checkout;

use Application\Process\Context;

interface StepInterface
{
    public function display(Context $context);
    public function forward(Context $context);
}