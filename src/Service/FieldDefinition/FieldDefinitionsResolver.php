<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<FieldDefinitionsProviderInterface>
 */
class FieldDefinitionsResolver extends AbstractProviderService implements FieldDefinitionsResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolveFieldDefinitions(string $entityClass, CrudOperation $crudOperation): ?array
    {
        foreach ($this->providers as $provider) {
            try {
                $fieldDefinitions = $provider->provideFieldDefinitions($entityClass);
                return array_filter(
                    $fieldDefinitions,
                    fn(FieldDefinition $fieldDefinition) => in_array(
                        $crudOperation,
                        $fieldDefinition->crudOperations,
                        true
                    )
                );
            } catch (UnsupportedByProviderException) {
                /* Continue */
            }
        }

        return null;
    }
}
