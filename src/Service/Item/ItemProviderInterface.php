<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface ItemProviderInterface extends ProviderInterface
{
    /**
     * @param CrudOperation   $crudOperation
     * @param class-string $entityClass
     * @param mixed           $id
     *
     * @return object|null
     * @throws UnsupportedByProviderException
     */
    public function provideItem(CrudOperation $crudOperation, string $entityClass, mixed $id): ?object;
}
