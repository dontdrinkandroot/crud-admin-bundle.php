<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\CrudOperation;

/**
 * Resolves the URL of an entity class for a CRUD operation.
 */
interface UrlResolverInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param T|null $entity
     */
    public function resolveUrl(string $entityClass, CrudOperation $crudOperation, ?object $entity = null): ?string;
}
