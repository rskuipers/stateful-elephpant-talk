<?php

namespace Application\Process;

use Application\Process\Checkout\StepInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Coordinator
{
    /**
     * @var StepInterface[]
     */
    protected $steps;

    /**
     * @var StepInterface[]
     */
    protected $orderedSteps;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $redirect;

    /**
     * @var \Closure
     */
    protected $validator;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param Request $request
     * @param string $stepName
     * @return RedirectResponse
     */
    public function forward(Request $request, $stepName)
    {
        if (!$this->validate()) {
            return $this->redirect();
        }

        if (!array_key_exists($stepName, $this->steps)) {
            throw new NotFoundHttpException("Step {$stepName} not found");
        }

        $context = $this->buildContext($request);

        $step = $context->getCurrentStep();

        $result = $step->forward($context);
        if ($result === true) {
            if ($context->getCurrentStep() === $context->getLastStep()) {
                return $this->redirect();
            }

            $nextStep = $context->getNextStep()->getName();

            return new RedirectResponse($this->getUrlGenerator()->generate('display', ['stepName' => $nextStep]));
        }

        return $result;
    }

    /**
     * @param Request $request
     * @param string $stepName
     * @return mixed
     */
    public function display(Request $request, $stepName)
    {
        if (!$this->validate()) {
            return $this->redirect();
        }

        if (!array_key_exists($stepName, $this->steps)) {
            throw new NotFoundHttpException("Step {$stepName} not found");
        }

        $context = $this->buildContext($request);

        $step = $context->getCurrentStep();

        return $step->display($context);
    }

    /**
     * @param StepInterface[] $steps
     */
    public function build(array $steps)
    {
        foreach ($steps as $step) {
            $this->add($step);
        }
    }


    /**
     * @param string $stepName
     * @return $this
     */
    protected function add($stepName)
    {
        $step = $this->app["step.{$stepName}"];

        $this->steps[$stepName] = $this->orderedSteps[] = $step;

        return $this;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        if (!$this->getValidator() instanceof \Closure) {
            return true;
        }
        $validator = $this->getValidator();
        return (bool) call_user_func($validator);
    }

    /**
     * @return RedirectResponse
     */
    public function redirect()
    {
        return new RedirectResponse($this->getUrlGenerator()->generate($this->getRedirect()));
    }

    /**
     * @param Request $request
     * @return Context
     */
    protected function buildContext(Request $request)
    {
        $currentStep = $this->steps[$request->get('stepName')];

        $context = new Context($this->orderedSteps, $currentStep);
        $context->setRequest($request);

        return $context;
    }

    /**
     * @return UrlGenerator
     */
    protected function getUrlGenerator()
    {
        return $this->app['url_generator'];
    }

    /**
     * @return string
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param string $redirect
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return callable
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param \Closure $validator
     */
    public function setValidator(\Closure $validator)
    {
        $this->validator = $validator;
    }
}
