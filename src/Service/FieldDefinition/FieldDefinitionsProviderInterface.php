<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface FieldDefinitionsProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param string $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return bool
     */
    public function supportsFieldDefinitions(string $crudOperation, string $entityClass): bool;

    /**
     * @template T of object
     *
     * @param string $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return array<array-key, FieldDefinition>
     */
    public function provideFieldDefinitions(string $crudOperation, string $entityClass): array;
}
