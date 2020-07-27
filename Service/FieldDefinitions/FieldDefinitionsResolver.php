<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttribute;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions\FieldDefinitionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class FieldDefinitionsResolver
{
    /** @var FieldDefinitionProviderInterface[] */
    private array $providers = [];

    public function addProvider(FieldDefinitionProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * @param Request $request
     *
     * @return FieldDefinition[]|null
     */
    public function resolve(Request $request): ?array
    {
        if (!$request->attributes->has(RequestAttribute::FIELD_DEFINITIONS)) {
            $request->attributes->set(RequestAttribute::FIELD_DEFINITIONS, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttribute::FIELD_DEFINITIONS);
    }

    /**
     * @param Request $request
     *
     * @return FieldDefinition[]|null
     */
    private function resolveFromProviders(Request $request)
    {
        foreach ($this->providers as $fieldDefinitionProvider) {
            if ($fieldDefinitionProvider->supports($request)) {
                $fieldDefinitions = $fieldDefinitionProvider->provideFieldDefinitions($request);
                if (null !== $fieldDefinitions) {
                    return $fieldDefinitions;
                }
            }
        }

        return null;
    }
}
