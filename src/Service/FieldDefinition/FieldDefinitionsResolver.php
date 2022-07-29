<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<FieldDefinitionsProviderInterface>
 */
class FieldDefinitionsResolver extends AbstractProviderService implements FieldDefinitionsResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(string $entityClass, CrudOperation $crudOperation): ?array
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof FieldDefinitionsProviderInterface);
            try {
                return $provider->provideFieldDefinitions($entityClass, $crudOperation);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
