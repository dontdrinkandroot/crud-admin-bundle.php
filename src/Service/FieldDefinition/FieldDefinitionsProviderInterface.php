<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @template T of object
 */
interface FieldDefinitionsProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     *
     * @return array<array-key, FieldDefinition>|null
     */
    public function provideFieldDefinitions(string $entityClass): ?array;
}
