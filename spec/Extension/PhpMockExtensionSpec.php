<?php

namespace spec\PhpSpec\PhpMock\Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\ServiceContainer;
use PhpSpec\Wrapper\Unwrapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpSpec\PhpMock\Runner\Maintainer\FunctionCollaboratorMaintainer;

class PhpMockExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\PhpMock\Extension\PhpMockExtension');
        $this->shouldImplement('PhpSpec\Extension');
    }
    
    function it_loads_the_collaborator_maintainer_into_the_container(ServiceContainer $container)
    {
        $container->define(
            'runner.maintainers.function_collaborator',
            Argument::type('Callable'), Argument::type('array'))
                  ->shouldBeCalled();
        $this->load($container);
    }
    
    function it_creates_the_collaborator_maintainer(
        ServiceContainer $container,
        Unwrapper $unwrapper, 
        EventDispatcherInterface $dispatcher
    ) {
        $container->get('unwrapper')->wilLReturn($unwrapper);
        $container->get('event_dispatcher')->willReturn($dispatcher);
        $this->createMaintainer($container)
             ->shouldReturnAnInstanceOf(FunctionCollaboratorMaintainer::class);
    }
}
