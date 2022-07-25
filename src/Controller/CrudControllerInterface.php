<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\CrudOperation;
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
     * @param CrudOperation $crudOperation
     * @param T|null $entity
     *
     * @return ?string
     */
    public function getUrl(CrudOperation $crudOperation, ?object $entity): ?string;
}
