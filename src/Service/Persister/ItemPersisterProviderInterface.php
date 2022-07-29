<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface ItemPersisterProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param CrudOperation   $crudOperation
     * @param T               $entity
     *
     * @return bool
     */

    public function supportsPersist(string $entityClass, CrudOperation $crudOperation, object $entity): bool;

    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param CrudOperation   $crudOperation
     * @param T               $entity
     */
    public function persist(string $entityClass, CrudOperation $crudOperation, object $entity): void;
}
