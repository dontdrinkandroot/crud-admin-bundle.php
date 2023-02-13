<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface PaginationTargetProvider extends ProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     * @return mixed
     */
    public function providePaginationTarget(string $entityClass): mixed;
}
