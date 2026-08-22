<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\Common\CrudOperation;

/**
 * Persists an entity for a CRUD operation.
 */
interface ItemPersisterInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @param T $entity
     */
    public function persistItem(CrudOperation $crudOperation, string $entityClass, object $entity): void;
}
