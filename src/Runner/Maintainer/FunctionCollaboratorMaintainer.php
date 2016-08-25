<?php

namespace PhpSpec\PhpMock\Runner\Maintainer;

use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\SpecificationInterface;
use PhpSpec\PhpMock\Wrapper\FunctionCollaborator;
use phpmock\prophecy\FunctionProphecy;
use Prophecy\Prophet;
use phpmock\prophecy\ReferencePreservingRevealer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;
use PhpSpec\Event\EventInterface;
use PhpSpec\Wrapper\Unwrapper;
use PhpSpec\Loader\Transformer\TypeHintIndex;
use phpmock\MockRegistry;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\Event\MethodCallEvent;

class FunctionCollaboratorMaintainer implements MaintainerInterface
{

    /**
     * @var Prophet
     */
    protected $prophet;
    
    /**
     * @var MockRegistry
     */
    protected $mockregistry;
    
    /**
     * @var Dispatcher
     */
    protected $dispatcher;
    
    /**
     * @var FunctionCollaborator
     */
    protected $collaborator;
    
    /**
     * @param Unwrapper $unwrapper
     * @param TypeHintIndex $typeHintIndex
     */
    public function __construct(
        Dispatcher $dispatcher,
        Prophet $prophet,
        MockRegistry $mockregistry
    )
    {
        $this->mockregistry = $mockregistry;
        $this->dispatcher = $dispatcher;
        $this->prophet = $prophet;
    }
    
    /**
     * Replaces a predefined collaborator named "$functions" with one using FunctionProphecy
     * 
     * {@inheritDoc}
     * @see \PhpSpec\Runner\Maintainer\CollaboratorsMaintainer::prepare()
     */
    public function prepare(
        ExampleNode $example,
        SpecificationInterface $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ) {
        if (!$collaborators->has('functions'))
            return false;
        
        $this->collaborator = new FunctionCollaborator($this->prophet, $example);
        $collaborators->set('functions', $this->collaborator);
        $this->dispatcher->addListener('beforeMethodCall', [$this, 'revealFunctionProphecy']);
        return true;
    }

    /**
     * Adds call to unmock all registered mocked functions
     * 
     * {@inheritDoc}
     * @see \PhpSpec\Runner\Maintainer\CollaboratorsMaintainer::teardown()
     */
    public function teardown(
        ExampleNode $example,
        SpecificationInterface $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ) {
        $this->mockregistry->unregisterAll();
        $this->prophet->checkPredictions();
    }
        
    public function revealFunctionProphecy(MethodCallEvent $event)
    {
        if (!isset($this->collaborator)) return;
        $this->collaborator->getWrappedObject();
        $this->collaborator = null;
    }

    /**
     * @param ExampleNode $example
     *
     * @return bool
     */
    public function supports(ExampleNode $example)
    {
        return true;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 40;
    }

}
