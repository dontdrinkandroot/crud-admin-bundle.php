<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface PaginationTargetProvider extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     *
     * @return bool
     */
    public function supportsPaginationTarget(string $entityClass): bool;

    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     *
     * @return ?string
     */
    public function providePaginationTarget(string $entityClass);
}
