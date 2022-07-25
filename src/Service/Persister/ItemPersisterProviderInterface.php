<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface ItemPersisterProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T $entity
     *
     * @return bool
     */

    public function supportsPersist(CrudOperation $crudOperation, string $entityClass, object $entity): bool;

    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T $entity
     */
    public function persist(CrudOperation $crudOperation, string $entityClass, object $entity): void;
}
