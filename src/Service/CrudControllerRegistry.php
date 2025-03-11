<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Dontdrinkandroot\CrudAdminBundle\Controller\CrudControllerInterface;
use RuntimeException;

/**
 * @template T of object
 */
class CrudControllerRegistry
{
    /**
     * @var array<class-string<T>, CrudControllerInterface<T>>
     */
    private array $controllersByEntityClass = [];

    /**
     * @var array<string, CrudControllerInterface<T>>
     */
    private array $controllersByServiceId = [];

    public function __construct()
    {
    }

    /**
     * @param class-string<T> $entityClass
     * @return CrudControllerInterface<T>|null
     */
    public function findControllerByEntityClass(string $entityClass): ?CrudControllerInterface
    {
        return $this->controllersByEntityClass[$entityClass] ?? null;
    }

    /**
     * @param class-string<T> $entityClass
     * @return CrudControllerInterface<T>
     */
    public function getControllerByEntityClass(string $entityClass): CrudControllerInterface
    {
        return $this->findControllerByEntityClass($entityClass)
            ?? throw new RuntimeException('No controller found for entityClass ' . $entityClass);
    }

    /**
     * @param CrudControllerInterface<T> $controller
     */
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

    /**
     * @return array<class-string<T>, CrudControllerInterface<T>>
     */
    public function getControllersByEntityClass(): array
    {
        return $this->controllersByEntityClass;
    }

    /**
     * @return array<string, CrudControllerInterface<T>>
     */
    public function getControllersByServiceId(): array
    {
        return $this->controllersByServiceId;
    }
}
