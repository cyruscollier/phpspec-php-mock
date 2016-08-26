# PhpSpec - PHP-Mock Extension

[![Build Status](https://travis-ci.org/cyruscollier/phpspec-php-mock.svg?branch=master)](https://travis-ci.org/cyruscollier/phpspec-php-mock)

Adds the PHP Mock function mocking library as a phpspec Collaborator

This [phpspec](http://www.phpspec.net/) extension allows you to mock non-deterministic PHP core functions (`time()`, `rand()`, etc.), or mock functions from other libraries or frameworks that have side effects from dependencies like a database, filesystem or HTTP request. 

By using the specially named parameter `$functions` in any example method, **phpspec-php-mock** will turn that parameter into a special `FunctionCollaborator` that wraps the [php-mock-prophecy](https://github.com/php-mock/php-mock-prophecy) library's `PHPProphet`. This allows you to mock return values for *any* function as you normally would for an `ObjectProphecy`.

## Installation

Add this to your composer.json:

```

    {
        "require-dev": {
            "cyruscollier/phpspec-php-mock": "dev-master"
        }
    }
    
```

Then add this to your phpspec.yml:

```

    extensions:
        - PhpSpec\PhpMock\Extension\PhpMockExtension

```

## Example

A PHP class that uses a non-deterministic function:

```php

    class Time
    {
        function getCurrentTime()
        {
            return time();
        }
    }
```

The spec for that class that mocks the `time()` function:

```php

    use PhpSpec\ObjectBehavior;
    
    class TimeSpec extends ObjectBehavior
    {
        function it_is_initializable()
        {
            $this->shouldHaveType('Time');
        }
    
        function it_gets_the_current_time($functions)
        {
            $functions->time()->willReturn(123);
            $this->getCurrentTime()->shouldReturn(123);
        }
    }

```
