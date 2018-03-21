<?php

namespace PhpSpec\PhpMock\Prophecy;

use Prophecy\Prophecy\ProphecyInterface;

class FunctionProphecy implements ProphecyInterface
{
    protected $prophecy;

    function __construct(\phpmock\prophecy\FunctionProphecy $prophecy)
    {
        $this->prophecy = $prophecy;
    }

    public function reveal()
    {
        $this->prophecy->reveal();
    }

    public function __call($functionName, array $arguments)
    {
        return $this->prophecy->__call($functionName, $arguments);
    }


}