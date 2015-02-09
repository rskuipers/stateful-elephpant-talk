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
    protected $redirectRoute;

    /**
     * @var \Closure
     */
    protected $validator;

    /**
     * @var string
     */
    protected $displayRoute;

    /**
     * @var string
     */
    protected $forwardRoute;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function start(Request $request)
    {
        if (!$this->validate()) {
            return $this->redirect();
        }

        $context = $this->buildContext($request);

        return new RedirectResponse($this->getUrlGenerator()->generate($this->getDisplayRoute(), [
            'stepName' => $context->getFirstStep()->getName(),
        ]));
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

            return new RedirectResponse($this->getUrlGenerator()->generate($this->getDisplayRoute(), [
                'stepName' => $nextStep
            ]));
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
     * @param string[] $steps
     * @return $this
     */
    public function build(array $steps)
    {
        foreach ($steps as $step) {
            $this->add($step);
        }

        return $this;
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
        return (bool)call_user_func($validator);
    }

    /**
     * @return RedirectResponse
     */
    public function redirect()
    {
        return new RedirectResponse($this->getUrlGenerator()->generate($this->getRedirectRoute()));
    }

    /**
     * @param Request $request
     * @return Context
     */
    protected function buildContext(Request $request)
    {
        $stepName = $request->get('stepName');

        $currentStep = $this->steps[$stepName ?: $this->orderedSteps[0]->getName()];

        $context = new Context($this->orderedSteps, $currentStep, $this);
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
    public function getRedirectRoute()
    {
        return $this->redirectRoute;
    }

    /**
     * @param string $redirectRoute
     * @return $this
     */
    public function setRedirectRoute($redirectRoute)
    {
        $this->redirectRoute = $redirectRoute;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayRoute()
    {
        return $this->displayRoute;
    }

    /**
     * @param string $displayRoute
     * @return $this
     */
    public function setDisplayRoute($displayRoute)
    {
        $this->displayRoute = $displayRoute;
        return $this;
    }

    /**
     * @return string
     */
    public function getForwardRoute()
    {
        return $this->forwardRoute;
    }

    /**
     * @param string $forwardRoute
     * @return $this
     */
    public function setForwardRoute($forwardRoute)
    {
        $this->forwardRoute = $forwardRoute;
        return $this;
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
     * @return $this
     */
    public function setValidator(\Closure $validator)
    {
        $this->validator = $validator;
        return $this;
    }
}
