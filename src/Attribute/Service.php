<?php

declare(strict_types=1);

namespace Bono\Service\Attribute;

use Attribute;
use Bono\Service\Builder\ReflectionBasedFactoryBuilder;
use DI\Container;

#[Attribute(Attribute::TARGET_CLASS)]
class Service
{
    /**
     * @var Container $container
     */
    private static Container $container;

    /**
     * @var ReflectionBasedFactoryBuilder $reflection
     */
    private static ReflectionBasedFactoryBuilder $reflection;

    /**
     * @return void
     */
    public static function getFactoryBuilder(): void
    {
        if (!isset(self::$reflection)) {
            self::$reflection = new ReflectionBasedFactoryBuilder(
                self::getContainer()
            );
        }
    }

    /**
     * @param Container $container
     * @return void
     */
    public static function setContainer(Container $container): void
    {
        self::$container = $container;
    }

    /**
     * @return Container
     */
    public static function getContainer(): Container
    {
        if (!isset(self::$container)) {
            throw new \RuntimeException(
                'Container not initialized.'
            );
        }
        return self::$container;
    }

    /**
     * @param string $className
     * @return callable
     */
    private static function buildFactory(string $className): callable
    {
        self::getFactoryBuilder();
        return self::$reflection->build($className);
    }

    /**
     * @param string $className
     * @return void
     */
    public function register(string $className)
    {
        Service::$container->set(
            $className,
            static::buildFactory($className)
        );
    }
}