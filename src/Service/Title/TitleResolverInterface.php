<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;

interface TitleResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @param T|null $entity
     */
    public function resolveTitle(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?string;
}
