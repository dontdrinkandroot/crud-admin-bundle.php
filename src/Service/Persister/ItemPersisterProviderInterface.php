<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface ItemPersisterProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     * @param T $entity
     *
     * @return bool
     */

    public function supportsPersist(string $crudOperation, string $entityClass, object $entity): bool;

    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     * @param T $entity
     */
    public function persist(string $crudOperation, string $entityClass, object $entity): void;
}
