<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class FieldDefinitionsResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return array<array-key, FieldDefinition>|null
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?array
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof FieldDefinitionsProviderInterface);
            try {
                return $provider->provideFieldDefinitions($crudOperation, $entityClass);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
