<?php

namespace PhpSpec\PhpMock\Prophecy;

use Prophecy\Prophecy\ProphecyInterface;

interface ProphetInterface
{
    function prophesize($thing): ProphecyInterface;

    function checkPredictions();
}