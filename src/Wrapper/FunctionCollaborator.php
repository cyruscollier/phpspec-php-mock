<?php

namespace PhpSpec\PhpMock\Wrapper;

use PhpSpec\Wrapper\WrapperInterface;
use phpmock\prophecy\FunctionProphecy;

class FunctionCollaborator implements WrapperInterface
{
    /**
     * @var FunctionProphecy
     */
    private $prophecy;

    /**
     * @param FunctionProphecy $prophecy
     */
    public function __construct(FunctionProphecy $prophecy)
    {
        $this->prophecy  = $prophecy;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        return call_user_func_array(array($this->prophecy, '__call'), array($method, $arguments));
    }

    /**
     * @param string $parameter
     * @param mixed  $value
     */
    public function __set($parameter, $value)
    {
        $this->prophecy->$parameter = $value;
    }

    /**
     * @param string $parameter
     *
     * @return mixed
     */
    public function __get($parameter)
    {
        return $this->prophecy->$parameter;
    }

    /**
     * @return object
     */
    public function getWrappedObject()
    {
        return $this->prophecy->reveal();
    }
}
