<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class FieldDefinitionsResolver extends AbstractProviderService
{
    /**
     * @param Request $request
     *
     * @return FieldDefinition[]|null
     */
    public function resolve(CrudAdminContext $context): ?array
    {
        if (!$context->isFieldDefinitionsResolved()) {
            $context->setFieldDefinitions($this->resolveFromProviders($context));
            $context->setFieldDefinitionsResolved();
        }

        return $context->getFieldDefinitions();
    }

    /**
     * @param Request $request
     *
     * @return FieldDefinition[]|null
     */
    private function resolveFromProviders(CrudAdminContext $context): ?array
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof FieldDefinitionsProviderInterface);
            if ($provider->supports($context)) {
                $fieldDefinitions = $provider->provideFieldDefinitions($context);
                if (null !== $fieldDefinitions) {
                    return $fieldDefinitions;
                }
            }
        }

        return null;
    }
}
