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
        $container->set('runner.maintainers.collaborators', function (ServiceContainer $c) {
            return new CollaboratorsMaintainer(
                $c->get('unwrapper'),
                $c->get('loader.transformer.typehintindex'),
                $c->get('event_dispatcher')
            );
        });
    }
}