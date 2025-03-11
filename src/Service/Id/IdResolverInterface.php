<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\Common\CrudOperation;

interface IdResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @param T $entity
     */
    public function resolveId(string $entityClass, CrudOperation $crudOperation, object $entity): mixed;
}
