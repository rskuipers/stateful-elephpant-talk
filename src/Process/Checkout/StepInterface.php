<?php

namespace Application\Process\Checkout;

use Symfony\Component\HttpFoundation\Request;

interface StepInterface
{
    public function display(Request $request);
    public function forward(Request $request);
}