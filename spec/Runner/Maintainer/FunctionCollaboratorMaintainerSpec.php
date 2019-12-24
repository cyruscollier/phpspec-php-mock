<?php

namespace spec\PhpSpec\PhpMock\Runner\Maintainer;

use PhpSpec\Locator\Resource;
use PhpSpec\ObjectBehavior;
use PhpSpec\Specification;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Loader\Node\SpecificationNode;

class FunctionCollaboratorMaintainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\PhpMock\Runner\Maintainer\FunctionCollaboratorMaintainer');
        $this->shouldImplement('PhpSpec\Runner\Maintainer\Maintainer');
    }
    
    function let(
        EventDispatcherInterface $dispatcher
    ) {
        $this->beConstructedWith($dispatcher);
    }
        
    function it_prepares_and_tears_down_an_example_with_a_function_prophecy_collaborator(
        ExampleNode $example,
        Specification $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators,
        SpecificationNode $specification,
        Resource $resource
    ) {
        $collaborators->has('functions')->willReturn(false);
        $collaborators->set('functions', Argument::any())->shouldNotBeCalled();
        $this->prepare($example, $context, $matchers, $collaborators)->shouldReturn(null);
        $this->teardown($example, $context, $matchers, $collaborators)->shouldReturn(null);
        
        $collaborators->has('functions')->willReturn(true);
        $example->getSpecification()->willReturn($specification);
        $resource->getSrcNamespace()->willReturn('');
        $specification->getResource()->willReturn($resource);
        $collaborators->set('functions', Argument::any())->shouldBeCalled();
        $this->prepare($example, $context, $matchers, $collaborators)->shouldReturn(null);
        $this->teardown($example, $context, $matchers, $collaborators)->shouldReturn(null);
    }

}
