<?php

namespace Bono\Service\Builder;

use DI\Container;
use ReflectionClass;

class ReflectionBasedFactoryBuilder
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $className
     * @return callable
     * @throws \InvalidArgumentException
     */
    public function build(string $className)
    {
        $container = $this->container;

        return function () use ($className, $container) {
            if (!class_exists($className)) {
                throw new \InvalidArgumentException(
                    "Class $className does not exist."
                );
            }
            $reflectionClass = new ReflectionClass($className);
            $constructor = $reflectionClass->getConstructor();
            $parameters = $constructor->getParameters();
            $dependencies = [];

            foreach ($parameters as $parameter) {
                $dependency = $parameter->getType()->getName();

                if ($dependency) {
                    $dependencies[] = $container->get($dependency);
                }
            }
            return $reflectionClass->newInstanceArgs($dependencies);
        };
    }
}