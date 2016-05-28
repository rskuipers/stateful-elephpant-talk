<?php

namespace Application\Process;

use Application\Process\Checkout\Step;
use Symfony\Component\HttpFoundation\Request;

class Context
{
    /**
     * @var Step[]
     */
    protected $steps;

    /**
     * @var Step
     */
    protected $currentStep;

    /**
     * @var Step|null
     */
    protected $nextStep;

    /**
     * @var Step|null
     */
    protected $previousStep;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Checkout
     */
    protected $checkout;

    /**
     * @param Step[] $steps
     * @param Step $currentStep
     * @param Checkout $checkout
     */
    public function __construct(array $steps, Step $currentStep, Checkout $checkout)
    {
        $this->steps = $steps;
        $this->currentStep = $currentStep;
        $this->checkout = $checkout;

        foreach ($steps as $index => $step) {
            if ($step === $currentStep) {
                $this->previousStep = $index-1 >= 0 ? $this->steps[$index-1] : null;
                $this->nextStep = $index+1 < count($this->steps) ?$this->steps[$index+1] : null;
            }
        }
    }

    /**
     * @return Step
     */
    public function getCurrentStep()
    {
        return $this->currentStep;
    }

    /**
     * @return Step
     */
    public function getPreviousStep()
    {
        return $this->previousStep;
    }

    /**
     * @return Step
     */
    public function getNextStep()
    {
        return $this->nextStep;
    }

    /**
     * @return Step
     */
    public function getFirstStep()
    {
        return $this->steps[0];
    }

    /**
     * @return Step
     */
    public function getLastStep()
    {
        return end($this->steps);
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Checkout
     */
    public function getCheckout()
    {
        return $this->checkout;
    }
}
