<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class FieldDefinitionsResolver implements ProviderServiceInterface
{
    /** @var FieldDefinitionProviderInterface[] */
    private array $providers = [];

    /**
     * {@inheritdoc}
     */
    public function addProvider(ProviderInterface $provider): void
    {
        assert($provider instanceof FieldDefinitionProviderInterface);
        $this->providers[] = $provider;
    }

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
