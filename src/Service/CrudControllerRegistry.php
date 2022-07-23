<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Dontdrinkandroot\CrudAdminBundle\Controller\CrudControllerInterface;
use RuntimeException;

class CrudControllerRegistry
{
    /**
     * @var array<string, CrudControllerInterface>
     */
    private array $controllersByEntityClass = [];

    /**
     * @param iterable<CrudControllerInterface> $controllers
     */
    public function __construct(iterable $controllers)
    {
        foreach ($controllers as $controller) {
            $this->controllersByEntityClass[$controller->getEntityClass()] = $controller;
        }
    }

    /**
     * @template T
     *
     * @param class-string<T> $entityClass
     *
     * @return CrudControllerInterface<T>|null
     */
    public function findControllerByEntityClass(string $entityClass): ?CrudControllerInterface
    {
        return $this->controllersByEntityClass[$entityClass] ?? null;
    }

    /**
     * @template T
     *
     * @param class-string<T> $entityClass
     *
     * @return CrudControllerInterface<T>
     */
    public function getControllerByEntityClass(string $entityClass): CrudControllerInterface
    {
        return $this->findControllerByEntityClass($entityClass)
            ?? throw new RuntimeException('No controller found for entityClass ' . $entityClass);
    }
}