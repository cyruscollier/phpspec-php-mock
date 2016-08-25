<?php

namespace PhpSpec\PhpMock;

class FunctionExample
{
    function getCurrentTime()
    {
        return time();
    }
    
    function getRandomNumber($min = 1, $max = 1000)
    {
        return rand($min, $max);
    }
    
    function addRandomNumberToCurrentTime()
    {
        return $this->getCurrentTime() + $this->getRandomNumber();
    }
    
    function methodWithoutAFunction()
    {
        return true;
    }
}
