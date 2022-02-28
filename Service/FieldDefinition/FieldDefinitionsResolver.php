<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class FieldDefinitionsResolver extends AbstractProviderService
{
    /** @return list<FieldDefinition>|null */
    public function resolve(CrudAdminContext $context): ?array
    {
        if (!$context->isFieldDefinitionsResolved()) {
            $context->setFieldDefinitions($this->resolveFromProviders($context));
            $context->setFieldDefinitionsResolved();
        }

        return $context->getFieldDefinitions();
    }

    /** @return list<FieldDefinition>|null */
    private function resolveFromProviders(CrudAdminContext $context): ?array
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof FieldDefinitionsProviderInterface);
            if ($provider->supportsFieldDefinitions($context)) {
                if (null !== $fieldDefinitions = $provider->provideFieldDefinitions($context)) {
                    return $fieldDefinitions;
                }
            }
        }

        return null;
    }
}
