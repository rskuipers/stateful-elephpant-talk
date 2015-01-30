<?php

namespace Application\Process;

use Application\Process\Checkout\StepInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Coordinator
{
    /**
     * @var StepInterface[]
     */
    protected $steps;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->add('details', '/checkout/details')
            ->add('payment', '/checkout/payment')
            ->add('review', '/checkout/review');
    }

    public function forward(Request $request, $stepName)
    {
        if (!array_key_exists($stepName, $this->steps)) {
            throw new NotFoundHttpException('Step not found');
        }

        $step = $this->app["step.{$stepName}"];

        $result = $step->forward($request);
        if ($result === true) {
            while (key($this->steps) !== $stepName) {
                next($this->steps);
            }

            $next = next($this->steps);

            return new RedirectResponse($next);
        }

        return $result;
    }

    public function display(Request $request, $stepName)
    {
        if (!array_key_exists($stepName, $this->steps)) {
            throw new NotFoundHttpException('Step not found');
        }

        $step = $this->app["step.{$stepName}"];

        return $step->display($request);
    }

    /**
     * @param $step
     * @param $route
     * @return $this
     */
    protected function add($step, $route)
    {
        $this->steps[$step] = $route;

        return $this;
    }
}
