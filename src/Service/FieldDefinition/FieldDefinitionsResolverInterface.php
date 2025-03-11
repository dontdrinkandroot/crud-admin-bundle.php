<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

interface FieldDefinitionsResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @return array<array-key, FieldDefinition>|null
     */
    public function resolveFieldDefinitions(string $entityClass, CrudOperation $crudOperation): ?array;
}
