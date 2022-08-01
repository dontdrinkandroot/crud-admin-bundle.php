<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface PaginationTargetProvider extends ProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     * @return mixed
     * @throws UnsupportedByProviderException
     */
    public function providePaginationTarget(string $entityClass): mixed;
}
