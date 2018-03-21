<?php

namespace spec\PhpSpec\PhpMock\Wrapper;

use PhpSpec\ObjectBehavior;
use PhpSpec\PhpMock\Prophecy\FunctionProphecy;
use PhpSpec\PhpMock\Prophecy\FunctionProphet;
use Prophecy\Argument;
use PhpSpec\Locator\Resource;

class FunctionCollaboratorSpec extends ObjectBehavior
{
    
    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\PhpMock\Wrapper\FunctionCollaborator');
        $this->shouldImplement('PhpSpec\Wrapper\ObjectWrapper');
    }
    
    function let(Resource $resource, FunctionProphet $prophet) {
        $this->beConstructedWith($resource, $prophet);
        $resource->getSrcNamespace()->willReturn('TestNamespace');
    }

    function it_gets_the_namespace()
    {
        $this->getNamespace()->shouldReturn('TestNamespace');
    }

    function it_sets_the_namespace()
    {
        $this->setNamespace('SomeOtherNamespace');
        $this->getNamespace()->shouldReturn('SomeOtherNamespace');
    }

    function it_adds_a_function_prophecy(FunctionProphet $prophet, FunctionProphecy $prophecy)
    {
        $prophet->prophesize('TestNamespace')->willReturn($prophecy);
        $this->prophesize()->shouldReturn($prophecy);
    }

    function it_adds_a_function_prophecy_with_a_new_namespace(
        FunctionProphet $prophet,
        FunctionProphecy $prophecy1,
        FunctionProphecy $prophecy2
    )
    {
        $prophet->prophesize('TestNamespace')->willReturn($prophecy1);
        $this->prophesize()->shouldReturn($prophecy1);
        $prophet->prophesize('SomeOtherNamespace')->willReturn($prophecy2);
        $this->prophesize('SomeOtherNamespace')->shouldReturn($prophecy2);
    }

    function it_reveals_the_function_prophecy(FunctionProphet $prophet, FunctionProphecy $prophecy)
    {
        $this->reveal()->shouldBe(false);
        $prophet->prophesize('TestNamespace')->willReturn($prophecy);
        $prophecy->reveal()->shouldBeCalled();
        $this->prophesize();
        $this->reveal()->shouldBe(true);
    }
    
}
