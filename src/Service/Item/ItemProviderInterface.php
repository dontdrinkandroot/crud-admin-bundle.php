<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface ItemProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param mixed           $id
     *
     * @return bool
     */
    public function supportsItem(CrudOperation $crudOperation, string $entityClass, mixed $id): bool;

    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param mixed           $id
     *
     * @return T|null
     */
    public function provideItem(CrudOperation $crudOperation, string $entityClass, mixed $id): ?object;
}
