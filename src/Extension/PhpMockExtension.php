<?php 

namespace PhpSpec\PhpMock\Extension;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;
use PhpSpec\PhpMock\Runner\Maintainer\FunctionCollaboratorMaintainer;
use Prophecy\Prophet;
use phpmock\MockRegistry;

class PhpMockExtension implements ExtensionInterface
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container)
    {
        $container->set('runner.maintainers.function_collaborator', [$this, 'createMaintainer']);
    }
    
    public function createMaintainer(ServiceContainer $container)
    {
        return new FunctionCollaboratorMaintainer(
            $container->get('event_dispatcher'),
            new Prophet(null, $container->get('unwrapper'), null),
            MockRegistry::getInstance()
        );
    }
}