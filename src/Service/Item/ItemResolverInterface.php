<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\EntityNotFoundException;

interface ItemResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @return T|null
     * @throws EntityNotFoundException
     */
    public function resolveItem(string $entityClass, CrudOperation $crudOperation, mixed $id): ?object;
}
