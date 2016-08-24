<?php

namespace spec\PhpSpec\PhpMock\Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\ServiceContainer;
use PhpSpec\Wrapper\Unwrapper;
use PhpSpec\Loader\Transformer\TypeHintIndex;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpSpec\PhpMock\Runner\Maintainer\CollaboratorsMaintainer;

class PhpMockExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\PhpMock\Extension\PhpMockExtension');
        $this->shouldImplement('PhpSpec\Extension\ExtensionInterface');
    }
    
    function it_loads_the_collaborator_maintainer_into_the_container(ServiceContainer $container)
    {
        $container->set('runner.maintainers.collaborators', Argument::type('Callable'))
                  ->shouldBeCalled();
        $this->load($container);
    }
    
    function it_creates_the_collaborator_maintainer(
        ServiceContainer $container,
        Unwrapper $unwrapper, 
        TypeHintIndex $typeHintIndex,
        EventDispatcherInterface $dispatcher
    ) {
        $container->get('unwrapper')->wilLReturn($unwrapper);
        $container->get('loader.transformer.typehintindex')->willReturn($typeHintIndex);
        $container->get('event_dispatcher')->willReturn($dispatcher);
        $this->createMaintainer($container)
             ->shouldReturnAnInstanceOf(CollaboratorsMaintainer::class);
    }
}
