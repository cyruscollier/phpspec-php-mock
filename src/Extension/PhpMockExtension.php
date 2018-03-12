<?php 

namespace PhpSpec\PhpMock\Extension;

use PhpSpec\Extension;
use PhpSpec\ServiceContainer;
use PhpSpec\ServiceContainer\IndexedServiceContainer;
use PhpSpec\PhpMock\Runner\Maintainer\FunctionCollaboratorMaintainer;
use Prophecy\Prophet;
use phpmock\MockRegistry;

class PhpMockExtension implements Extension
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container, array $params = [])
    {
        $container->define('runner.maintainers.function_collaborator', [$this, 'createMaintainer']);
    }
    
    public function createMaintainer(IndexedServiceContainer $container)
    {
        return new FunctionCollaboratorMaintainer(
            $container->get('event_dispatcher'),
            new Prophet(null, $container->get('unwrapper'), null),
            MockRegistry::getInstance()
        );
    }
}