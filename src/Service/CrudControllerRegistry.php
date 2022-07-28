<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Dontdrinkandroot\CrudAdminBundle\Controller\CrudControllerInterface;
use RuntimeException;

class CrudControllerRegistry
{
    /**
     * @var array<class-string, CrudControllerInterface>
     */
    private array $controllersByEntityClass = [];

    /**
     * @var array<string, CrudControllerInterface>
     */
    private array $controllersByServiceId = [];

    public function __construct()
    {
    }

    /**
     * @template       T
     *
     * @param class-string<T> $entityClass
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @return CrudControllerInterface<T>|null
     */
    public function findControllerByEntityClass(string $entityClass): ?CrudControllerInterface
    {
        return $this->controllersByEntityClass[$entityClass] ?? null;
    }

    /**
     * @template       T
     *
     * @param class-string<T> $entityClass
     *
     * @psalm-suppress InvalidReturnType
     * @return CrudControllerInterface<T>
     */
    public function getControllerByEntityClass(string $entityClass): CrudControllerInterface
    {
        return $this->findControllerByEntityClass($entityClass)
            ?? throw new RuntimeException('No controller found for entityClass ' . $entityClass);
    }

    public function registerController(string $serviceId, CrudControllerInterface $controller): void
    {
        $entityClass = $controller->getEntityClass();
        if (array_key_exists($serviceId, $this->controllersByServiceId)) {
            throw new RuntimeException(sprintf('There is already a controller for service id %s', $serviceId));
        }
        if (array_key_exists($entityClass, $this->controllersByEntityClass)) {
            throw new RuntimeException(sprintf('There is already a controller for entity class %s', $entityClass));
        }
        $this->controllersByServiceId[$serviceId] = $controller;
        $this->controllersByEntityClass[$entityClass] = $controller;
    }

    public function getControllersByEntityClass(): array
    {
        return $this->controllersByEntityClass;
    }

    public function getControllersByServiceId(): array
    {
        return $this->controllersByServiceId;
    }
}
