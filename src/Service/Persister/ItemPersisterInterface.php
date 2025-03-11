<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\Common\CrudOperation;

interface ItemPersisterInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @param T $entity
     */
    public function persistItem(CrudOperation $crudOperation, string $entityClass, object $entity): void;
}
