<?php

namespace Bono\Service\Builder;

use Psr\Container\ContainerInterface;
use ReflectionClass;

class ReflectionBasedFactoryBuilder
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
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