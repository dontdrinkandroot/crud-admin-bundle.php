<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

interface FieldDefinitionsResolverInterface
{
    /**
     * @param class-string  $entityClass
     *
     * @return array<array-key, FieldDefinition>|null
     */
    public function resolveFieldDefinitions(string $entityClass, CrudOperation $crudOperation): ?array;
}
