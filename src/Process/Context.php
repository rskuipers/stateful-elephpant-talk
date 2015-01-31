<?php

namespace Application\Process;

use Application\Process\Checkout\StepInterface;
use Symfony\Component\HttpFoundation\Request;

class Context
{
    /**
     * @var StepInterface[]
     */
    protected $steps;

    /**
     * @var StepInterface
     */
    protected $currentStep;

    /**
     * @var StepInterface
     */
    protected $nextStep;

    /**
     * @var StepInterface
     */
    protected $previousStep;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param array $steps
     * @param StepInterface $currentStep
     */
    public function __construct(array $steps, StepInterface $currentStep)
    {
        $this->steps = $steps;
        $this->currentStep = $currentStep;

        foreach ($steps as $index => $step) {
            if ($step === $currentStep) {
                $this->previousStep = $this->steps[$index-1];
                $this->nextStep = $this->steps[$index+1];
            }
        }
    }

    /**
     * @return StepInterface
     */
    public function getCurrentStep()
    {
        return $this->currentStep;
    }

    /**
     * @return mixed
     */
    public function getPreviousStep()
    {
        return $this->previousStep;
    }

    /**
     * @return mixed
     */
    public function getNextStep()
    {
        return $this->nextStep;
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
}
