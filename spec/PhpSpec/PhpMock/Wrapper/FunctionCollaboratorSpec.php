<?php

namespace spec\PhpSpec\PhpMock\Wrapper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use phpmock\prophecy\FunctionProphecy;
use Prophecy\Prophecy\ProphecyInterface;
use Prophecy\Prophet;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Loader\Node\SpecificationNode;
use PhpSpec\Locator\ResourceInterface;

class FunctionCollaboratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\PhpMock\Wrapper\FunctionCollaborator');
    }
    
    function let(
        Prophet $prophet, 
        ExampleNode $example, 
        SpecificationNode $specification, 
        ResourceInterface $resource
    ) {
        $this->beConstructedWith($prophet, $example);
        $example->getSpecification()->willReturn($specification);
        $specification->getResource()->willReturn($resource);
        $resource->getSrcNamespace()->willReturn('TestNamespace');
    }
}
