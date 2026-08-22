<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

/**
 * Resolves the field definitions of an entity class for a CRUD operation.
 */
interface FieldDefinitionsResolverInterface
{
    /**
     * @param class-string $entityClass
     * @return array<array-key, FieldDefinition>|null
     */
    public function resolveFieldDefinitions(string $entityClass, CrudOperation $crudOperation): ?array;
}
