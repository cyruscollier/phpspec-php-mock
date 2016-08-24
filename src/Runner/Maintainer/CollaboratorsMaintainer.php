<?php

namespace PhpSpec\PhpMock\Runner\Maintainer;

use PhpSpec\Runner\Maintainer\CollaboratorsMaintainer as PhpSpecCollaboratorsMaintainer;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\SpecificationInterface;
use PhpSpec\PhpMock\Wrapper\FunctionCollaborator;
use phpmock\MockRegistry;
use phpmock\prophecy\FunctionProphecy;

class CollaboratorsMaintainer extends PhpSpecCollaboratorsMaintainer
{

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
        $reflection = new \ReflectionProperty($this, 'prophet');
        $reflection->setAccessible(true);
        $prophet = $reflection->getValue($this);
        $namespace = $example->getSpecification()->getResource()->getSrcNamespace();
        $collaborator = new FunctionCollaborator(new FunctionProphecy($namespace, $prophet));
        $collaborators->set($name, $collaborator);
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
        MockRegistry::getInstance()->unregisterAll();
        $this->prophet->checkPredictions();
    }

}
