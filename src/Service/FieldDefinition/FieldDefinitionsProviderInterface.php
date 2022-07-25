<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface FieldDefinitionsProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param CrudOperation $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return bool
     */
    public function supportsFieldDefinitions(CrudOperation $crudOperation, string $entityClass): bool;

    /**
     * @template T of object
     *
     * @param CrudOperation $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return array<array-key, FieldDefinition>
     */
    public function provideFieldDefinitions(CrudOperation $crudOperation, string $entityClass): array;
}
