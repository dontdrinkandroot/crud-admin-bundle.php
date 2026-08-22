<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

/**
 * Resolves the pagination target of an entity class.
 */
interface PaginationTargetResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     */
    public function resolvePaginationTarget(string $entityClass): mixed;
}
