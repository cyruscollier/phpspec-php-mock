<?php

namespace spec\PhpSpec\PhpMock\Runner\Maintainer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Wrapper\Unwrapper;
use PhpSpec\Loader\Transformer\TypeHintIndex;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CollaboratorsMaintainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\PhpMock\Runner\Maintainer\CollaboratorsMaintainer');
    }
    
    function let(
        Unwrapper $unwrapper, 
        TypeHintIndex $typeHintIndex, 
        EventDispatcherInterface $dispatcher
    ) {
        $this->beConstructedWith($unwrapper, $typeHintIndex, $dispatcher);
    }
}
