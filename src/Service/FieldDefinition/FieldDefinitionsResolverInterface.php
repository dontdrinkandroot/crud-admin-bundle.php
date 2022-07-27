<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

interface FieldDefinitionsResolverInterface
{
    /**
     * @param CrudOperation $crudOperation
     * @param class-string  $entityClass
     *
     * @return array<array-key, FieldDefinition>|null
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?array;
}
