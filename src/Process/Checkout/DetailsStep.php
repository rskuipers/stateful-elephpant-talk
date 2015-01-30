<?php

namespace Application\Process\Checkout;

use Symfony\Component\HttpFoundation\Request;

class DetailsStep implements StepInterface
{
    public function display(Request $request)
    {
        return $this->render();
    }

    public function forward(Request $request)
    {
        if ($request->get('email')) {
            return true;
        }

        return $this->render();
    }

    protected function render()
    {
        return '<form method="post" action="/checkout/details/forward"><input type="text" name="email" /><button>Submit</button></form>';
    }
}
