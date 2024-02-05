<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Override;

/**
 * @extends AbstractProviderService<FieldDefinitionsProviderInterface>
 */
class FieldDefinitionsResolver extends AbstractProviderService implements FieldDefinitionsResolverInterface
{
    #[Override]
    public function resolveFieldDefinitions(string $entityClass, CrudOperation $crudOperation): ?array
    {
        foreach ($this->providers as $provider) {
            $fieldDefinitions = $provider->provideFieldDefinitions($entityClass);
            if (null !== $fieldDefinitions) {
                return array_filter(
                    $fieldDefinitions,
                    fn(FieldDefinition $fieldDefinition) => in_array(
                        $crudOperation,
                        $fieldDefinition->crudOperations,
                        true
                    )
                );
            }
        }

        return null;
    }
}
