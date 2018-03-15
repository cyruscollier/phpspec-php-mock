<?php

namespace PhpSpec\PhpMock\Wrapper;

use PhpSpec\Wrapper\ObjectWrapper;
use phpmock\prophecy\PHPProphet;
use Prophecy\Prophecy\ProphecyInterface;
use PhpSpec\Locator\Resource;

class FunctionCollaborator implements ObjectWrapper
{
    /**
     * @var ProphecyInterface
     */
    protected $prophecy;
    
    /**
     * @var PHPProphet
     */
    protected $function_prophet;

    /**
     * @param Resource $resource
     */
    public function __construct(Resource $resource)
    {
        $this->function_prophet = new PHPProphet();
        $this->prophecy = $this->function_prophet->prophesize($resource->getSrcNamespace());
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        return call_user_func_array([$this->prophecy, '__call'], [$method, $arguments]);
    }
    
    public function checkProphetPredictions()
    {
        $this->function_prophet->checkPredictions();
    }

    /**
     * @return object
     */
    public function getWrappedObject()
    {

        if (!$this->prophecy) return;
        $object = $this->prophecy->reveal();
        $this->prophecy = null; //so functions only get mocked once per example
        return $object;
        
    }

    public function reveal()
    {
        $this->getWrappedObject();
    }
}
