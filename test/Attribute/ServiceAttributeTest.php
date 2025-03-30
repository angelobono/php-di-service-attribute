<?php

namespace Bono\Service\Test\Attribute;

use Bono\Service\Attribute\Service;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * ServiceAttributeTest
 */
class ServiceAttributeTest extends TestCase
{
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testAsServiceAttribute()
    {
        $container = new Container();
        Service::setContainer($container);
        $serviceInstance = $container->get(TestService::class);
        $this->assertTrue($serviceInstance instanceof TestService);
    }
}
