<?php 

namespace PhpSpec\PhpMock\Extension;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;
use PhpSpec\PhpMock\Runner\Maintainer\CollaboratorsMaintainer;

class PhpMockExtension implements ExtensionInterface
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container)
    {
        $container->set('runner.maintainers.collaborators', [$this, 'createMaintainer']);
    }
    
    public function createMaintainer(ServiceContainer $container)
    {
        return new CollaboratorsMaintainer(
            $container->get('unwrapper'),
            $container->get('loader.transformer.typehintindex'),
            $container->get('event_dispatcher')
        );
    }
}