<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;

/**
 * Resolves the title of an entity class for a CRUD operation.
 */
interface TitleResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @param T|null $entity
     */
    public function resolveTitle(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?string;
}
