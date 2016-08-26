<?php

namespace spec\PhpSpec\PhpMock\Wrapper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Locator\ResourceInterface;

class FunctionCollaboratorSpec extends ObjectBehavior
{
    
    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\PhpMock\Wrapper\FunctionCollaborator');
        $this->shouldImplement('PhpSpec\Wrapper\WrapperInterface');
    }
    
    function let(ResourceInterface $resource) {
        $this->beConstructedWith($resource);
        $resource->getSrcNamespace()->willReturn('TestNamespace');
    }
    
}
