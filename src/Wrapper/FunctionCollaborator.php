<?php

namespace PhpSpec\PhpMock\Wrapper;

use PhpSpec\PhpMock\Prophecy\FunctionProphet;
use PhpSpec\Wrapper\ObjectWrapper;
use Prophecy\Prophecy\ProphecyInterface;
use PhpSpec\Locator\Resource;

class FunctionCollaborator implements ObjectWrapper
{
    /**
     * @var ProphecyInterface[]
     */
    protected $prophecies = [];
    
    /**
     * @var FunctionProphet
     */
    protected $function_prophet;


    protected $namespace;

    /**
     * @param Resource $resource
     * @param FunctionProphet $function_prophet
     */
    public function __construct(Resource $resource, FunctionProphet $function_prophet = null)
    {
        $this->function_prophet = $function_prophet ?: new FunctionProphet();
        $this->namespace = $resource->getSrcNamespace();
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        return call_user_func_array([$this->prophesize(), '__call'], [$method, $arguments]);
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    
    public function checkProphetPredictions()
    {
        $this->function_prophet->checkPredictions();
    }

    public function getWrappedObject()
    {

        if (empty($this->prophecies)) return false;
        while(!empty($this->prophecies)) {
            $prophecy = array_pop($this->prophecies);
            $prophecy->reveal();
        }
        return true;
    }

    public function reveal()
    {
        return $this->getWrappedObject();
    }

    /**
     * @param string $namespace
     * @return ProphecyInterface
     */
    public function prophesize(string $namespace = '')
    {
        if ($namespace) {
            $this->setNamespace($namespace);
        }
        if (empty($this->prophecies) || $namespace) {
            $this->prophecies[] = $this->function_prophet->prophesize($this->getNamespace());
        }
        return end($this->prophecies);
    }
}
