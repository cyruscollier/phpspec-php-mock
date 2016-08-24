<?php

namespace spec\PhpSpec\PhpMock;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FunctionExampleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\PhpMock\FunctionExample');
    }
    
    function it_gets_the_current_time($functions)
    {
        $functions->time()->willReturn(123);
        $this->getCurrentTime()->shouldReturn(123);
    }
    
    function it_gets_a_random_number($functions)
    {
        $functions->rand(5, 10)->willReturn(1);
        $this->getRandomNumber(5, 10)->shouldReturn(1);
    }
    
    function it_adds_a_random_number_to_the_current_time($functions)
    {
        $functions->rand(1, 1000)->willReturn(234);
        $functions->time()->willReturn(1000);
        $this->addRandomNumberToCurrentTime()->shouldReturn(1234);
    }
}