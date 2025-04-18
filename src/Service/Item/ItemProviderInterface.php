<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\EntityNotFoundException;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @template T of object
 */
interface ItemProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @return T|null
     * @throws EntityNotFoundException
     */
    public function provideItem(string $entityClass, CrudOperation $crudOperation, mixed $id): ?object;
}
