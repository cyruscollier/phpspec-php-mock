<?php 

namespace PhpSpec\PhpMock\Extension;

use PhpSpec\Extension;
use PhpSpec\ServiceContainer;
use PhpSpec\PhpMock\Runner\Maintainer\FunctionCollaboratorMaintainer;

class PhpMockExtension implements Extension
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container, array $params = [])
    {
        $container->define(
            'runner.maintainers.function_collaborator',
            [$this, 'createMaintainer'],
            ['runner.maintainers']
        );
    }
    
    public function createMaintainer(ServiceContainer $container)
    {
        return new FunctionCollaboratorMaintainer(
            $container->get('event_dispatcher')
        );
    }
}