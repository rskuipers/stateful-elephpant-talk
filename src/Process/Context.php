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
     * @param StepInterface[] $steps
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
     * @return StepInterface
     */
    public function getPreviousStep()
    {
        return $this->previousStep;
    }

    /**
     * @return StepInterface
     */
    public function getNextStep()
    {
        return $this->nextStep;
    }

    /**
     * @return StepInterface
     */
    public function getFirstStep()
    {
        return $this->steps[0];
    }

    /**
     * @return StepInterface
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
}
