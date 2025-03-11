<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\CrudOperation;

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
