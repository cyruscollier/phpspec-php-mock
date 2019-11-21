<?php

namespace PhpSpec\PhpMock\Runner\Maintainer;

use PhpSpec\Runner\Maintainer\Maintainer;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Specification;
use PhpSpec\PhpMock\Wrapper\FunctionCollaborator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;
use PhpSpec\Event\MethodCallEvent;

class FunctionCollaboratorMaintainer implements Maintainer
{
    const FUNCTIONS_PARAMETER = 'functions';

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var FunctionCollaborator
     */
    protected $collaborator;

    /**
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Replaces a predefined collaborator named "$functions" with one using PHPProphecy
     *
     * {@inheritDoc}
     * @see \PhpSpec\Runner\Maintainer\CollaboratorsMaintainer::prepare()
     */
    public function prepare(
        ExampleNode $example,
        Specification $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ): void {
        if (!$collaborators->has(self::FUNCTIONS_PARAMETER)) {
            return;
        }
        $this->collaborator = new FunctionCollaborator($example->getSpecification()->getResource());
        $collaborators->set(self::FUNCTIONS_PARAMETER, $this->collaborator);

        $this->dispatcher->addListener('beforeMethodCall', [$this, 'revealFunctionProphecy']);
    }

    /**
     * Adds call to unmock all registered mocked functions
     *
     * {@inheritDoc}
     * @see \PhpSpec\Runner\Maintainer\CollaboratorsMaintainer::teardown()
     */
    public function teardown(
        ExampleNode $example,
        Specification $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ): void {
        if (!isset($this->collaborator)) {
            return;
        }
        $this->collaborator->checkProphetPredictions();
    }

    public function revealFunctionProphecy(MethodCallEvent $event)
    {
        if(!isset($this->collaborator)) return;
        $this->collaborator->getWrappedObject();
    }

    /**
     * @param ExampleNode $example
     *
     * @return bool
     */
    public function supports(ExampleNode $example): bool
    {
        return true;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 40;
    }

}
