<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions\FieldDefinitionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ItemResolver
{
    /** @var ItemProviderInterface[] */
    private array $providers = [];

    public function addProvider(ItemProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    public function resolve(Request $request): ?object
    {
        if (!$request->attributes->has(RequestAttributes::DATA)) {
            $request->attributes->set(RequestAttributes::DATA, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttributes::DATA);
    }

    private function resolveFromProviders(Request $request): ?object
    {
        foreach ($this->providers as $itemProvider) {
            if ($itemProvider->supports($request)){
                $entity = $itemProvider->provideItem($request);
                if (null !== $entity) {
                    return $entity;
                }
            }
        }

        return null;
    }
}
