<?php

namespace spec\PhpSpec\PhpMock\Runner\Maintainer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Wrapper\Unwrapper;
use PhpSpec\Loader\Transformer\TypeHintIndex;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\SpecificationInterface;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\PhpMock\FunctionExample;
use PhpSpec\Loader\Node\SpecificationNode;
use PhpSpec\PhpMock\Wrapper\FunctionCollaborator;
use PhpSpec\Locator\ResourceInterface;

class CollaboratorsMaintainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\PhpMock\Runner\Maintainer\CollaboratorsMaintainer');
    }
    
    function let(
        Unwrapper $unwrapper, 
        TypeHintIndex $typeHintIndex, 
        EventDispatcherInterface $dispatcher
    ) {
        $this->beConstructedWith($unwrapper, $typeHintIndex, $dispatcher);
    }
    
//     function it_prepares_an_example_with_a_function_prophecy_collaborator(
//         ExampleNode $example,
//         SpecificationInterface $context,
//         MatcherManager $matchers,
//         CollaboratorManager $collaborators
//     ) {
        
//         $this->prepare($example, $context, $matchers, $collaborators)
//     }
        
    function it_prepares_an_example_with_a_function_prophecy_collaborator(
        ExampleNode $example,
        SpecificationInterface $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators,
        SpecificationNode $specification,
        ResourceInterface $resource
    ) {
        $example->getSpecification()->willReturn($specification);
        $class = new \ReflectionClass(FunctionExample::class);
        $method = $class->getMethod('methodWithoutAFunction');
        $specification->getClassReflection()->willReturn($class);
        $example->getFunctionReflection()->willReturn($method);
        
        $collaborators->has('functions')->willReturn(false);
        $this->prepare($example, $context, $matchers, $collaborators);
        $collaborators->set('functions', Argument::any())
                      ->shouldNotHaveBeenCalled();
        
        $collaborators->has('functions')->willReturn(true);
        $specification->getResource()->willReturn($resource);
        $resource->getSrcNamespace()->willReturn($class->getNamespaceName());
        $collaborators->set('functions', Argument::any())
                      ->shouldBeCalled();
        $this->prepare($example, $context, $matchers, $collaborators);
        
    }
}
