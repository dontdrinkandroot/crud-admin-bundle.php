<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface FieldDefinitionsProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param CrudOperation   $crudOperation
     *
     * @return array<array-key, FieldDefinition>
     * @throws UnsupportedByProviderException
     */
    public function provideFieldDefinitions(string $entityClass, CrudOperation $crudOperation): array;
}
