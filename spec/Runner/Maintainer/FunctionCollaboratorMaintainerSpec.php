<?php

namespace spec\PhpSpec\PhpMock\Runner\Maintainer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\SpecificationInterface;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Loader\Node\SpecificationNode;
use PhpSpec\Locator\ResourceInterface;

class FunctionCollaboratorMaintainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\PhpMock\Runner\Maintainer\FunctionCollaboratorMaintainer');
        $this->shouldImplement('PhpSpec\Runner\Maintainer\MaintainerInterface');
    }
    
    function let(
        EventDispatcherInterface $dispatcher
    ) {
        $this->beConstructedWith($dispatcher);
    }
        
    function it_prepares_and_tears_down_an_example_with_a_function_prophecy_collaborator(
        ExampleNode $example,
        SpecificationInterface $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators,
        SpecificationNode $specification,
        ResourceInterface $resource
    ) {
        $collaborators->has('functions')->willReturn(false);
        $collaborators->set('functions', Argument::any())->shouldNotBeCalled();
        $this->prepare($example, $context, $matchers, $collaborators)->shouldReturn(false);
        $this->teardown($example, $context, $matchers, $collaborators)->shouldReturn(false);
        
        $collaborators->has('functions')->willReturn(true);
        $example->getSpecification()->willReturn($specification);
        $specification->getResource()->willReturn($resource);
        $collaborators->set('functions', Argument::any())->shouldBeCalled();
        $this->prepare($example, $context, $matchers, $collaborators)->shouldReturn(true);
        $this->teardown($example, $context, $matchers, $collaborators)->shouldReturn(true);
    }

}
