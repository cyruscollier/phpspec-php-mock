<?php

namespace PhpSpec\PhpMock\Prophecy;

use phpmock\prophecy\PHPProphet;
use Prophecy\Prophecy\ProphecyInterface;
use Prophecy\Prophet;

class FunctionProphet implements ProphetInterface
{
    protected $prophet;

    function __construct(Prophet $prophet = null)
    {
        $this->prophet = new PHPProphet($prophet);
    }

    function prophesize($thing): ProphecyInterface
    {
        $prophecy = $this->prophet->prophesize($thing);
        return new FunctionProphecy($prophecy);
    }

    function checkPredictions()
    {
        $this->prophet->checkPredictions();
    }

}