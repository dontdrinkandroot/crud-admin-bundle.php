<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
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
    public function resolve(Request $request): ?array
    {
        if (!$request->attributes->has(RequestAttributes::FIELD_DEFINITIONS)) {
            $request->attributes->set(RequestAttributes::FIELD_DEFINITIONS, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttributes::FIELD_DEFINITIONS);
    }

    /**
     * @param Request $request
     *
     * @return FieldDefinition[]|null
     */
    private function resolveFromProviders(Request $request)
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof FieldDefinitionProviderInterface);
            if ($provider->supportsRequest($request)) {
                $fieldDefinitions = $provider->provideFieldDefinitions($request);
                if (null !== $fieldDefinitions) {
                    return $fieldDefinitions;
                }
            }
        }

        return null;
    }
}
