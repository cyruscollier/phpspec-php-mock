<?php

namespace spec\PhpSpec\PhpMock\Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PhpMockExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\PhpMock\Extension\PhpMockExtension');
    }
}
