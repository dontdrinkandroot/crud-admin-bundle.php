<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class FieldDefinitionsResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return array<array-key, FieldDefinition>|null
     */
    public function resolve(string $crudOperation, string $entityClass): ?array
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof FieldDefinitionsProviderInterface);
            if ($provider->supportsFieldDefinitions($crudOperation, $entityClass)) {
                return $provider->provideFieldDefinitions($crudOperation, $entityClass);
            }
        }

        return null;
    }
}
