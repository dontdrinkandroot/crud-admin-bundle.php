<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

interface FieldDefinitionsResolverInterface
{
    /**
     * @param class-string  $entityClass
     * @param CrudOperation $crudOperation
     *
     * @return array<array-key, FieldDefinition>|null
     */
    public function resolve(string $entityClass, CrudOperation $crudOperation): ?array;
}
