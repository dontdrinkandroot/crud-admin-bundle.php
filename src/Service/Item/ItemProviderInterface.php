<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface ItemProviderInterface extends ProviderInterface
{
    /**
     * @param class-string  $entityClass
     * @param CrudOperation $crudOperation
     * @param mixed         $id
     *
     * @return object|null
     * @throws UnsupportedByProviderException
     */
    public function provideItem(string $entityClass, CrudOperation $crudOperation, mixed $id): ?object;
}
