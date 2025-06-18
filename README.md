# A PHP service attribute that automatically configures factories in a DI container

The factories based on constructor reflection

## Install

```bash
composer require angelobono/php-di-service-attribute
```

## Usage

If you have a class you can add the `#[Service]` attribute to it:

```php
#[Service]
class TestService1
{
  public function __construct(TestService2 $service2) {
    // ...
  }
}

#[Service]
class TestService2
{
}
```

Then you can use a Container to automatically get an instance of the service:

```php
$container = new Container();
Service::setContainer($container);
$serviceInstance = $container->get(TestService1::class);
$serviceInstance instanceof TestService1 === true;
```
