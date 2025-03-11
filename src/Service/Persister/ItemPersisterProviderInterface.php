<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @template T of object
 */
interface ItemPersisterProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @param T $entity
     */
    public function persist(string $entityClass, CrudOperation $crudOperation, object $entity): bool|null;
}
