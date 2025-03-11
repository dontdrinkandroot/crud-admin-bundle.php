<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

interface PaginationTargetResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     */
    public function resolvePaginationTarget(string $entityClass): mixed;
}
