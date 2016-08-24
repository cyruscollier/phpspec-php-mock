<?php

namespace PhpSpec\PhpMock\Runner\Maintainer;

use PhpSpec\Runner\Maintainer\CollaboratorsMaintainer as PhpSpecCollaboratorsMaintainer;
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

class CollaboratorsMaintainer extends PhpSpecCollaboratorsMaintainer
{

    /**
     * @var Prophet
     */
    protected $prophet;
    
    /**
     * @var Dispatcher
     */
    protected $dispatcher;
    
    /**
     * @var FunctionCollaborator
     */
    protected $function_collaborator;
    
    /**
     * @param Unwrapper $unwrapper
     * @param TypeHintIndex $typeHintIndex
     */
    public function __construct(
        Unwrapper $unwrapper, 
        TypeHintIndex $typeHintIndex = null,
        Dispatcher $dispatcher
    )
    {
        parent::__construct($unwrapper, $typeHintIndex);
        $this->dispatcher = $dispatcher;
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
        parent::prepare($example, $context, $matchers, $collaborators);
        if (!$collaborators->has('functions'))
            return;
        $reflection = new \ReflectionProperty(get_parent_class($this), 'prophet');
        $reflection->setAccessible(true);
        $prophet = $reflection->getValue($this);
        $revealer = new ReferencePreservingRevealer(self::getProphetProperty($prophet, "revealer"));
        $util = self::getProphetProperty($prophet, "util");
        $this->prophet = new Prophet($prophet->getDoubler(), $revealer, $util);
        $this->function_collaborator = new FunctionCollaborator($this->prophet, $example);
        $collaborators->set('functions', $this->function_collaborator);
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
        SpecificationInterface $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ) {

        if(isset($this->prophet)) {
            MockRegistry::getInstance()->unregisterAll();
            $this->prophet->checkPredictions();
        }
        parent::teardown($example, $context, $matchers, $collaborators);
    }
    
    public function revealFunctionProphecy(EventInterface $event)
    {
        if (empty( $this->function_collaborator)) return;
        $this->function_collaborator->getWrappedObject();
        $this->function_collaborator = null;
    }
    
    private static function getProphetProperty($object, $property)
    {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);
        return $reflection->getValue($object);
    }

}
