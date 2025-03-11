<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @template T of object
 */
interface PaginationTargetProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @return mixed
     */
    public function providePaginationTarget(string $entityClass): mixed;
}
