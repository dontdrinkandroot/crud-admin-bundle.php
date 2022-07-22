<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;

/**
 * @template T of object
 */
interface CrudControllerInterface
{
    /**
     * @return class-string<T>
     */
    public function getEntityClass(): string;

    /**
     * @param string $crudOperation
     * @param T|null $entity
     *
     * @return ?string
     */
    public function getUrl(string $crudOperation, ?object $entity): ?string;
}
