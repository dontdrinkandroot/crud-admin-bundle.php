<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ItemResolver implements ProviderServiceInterface
{
    /** @var ItemProviderInterface[] */
    private array $providers = [];

    /**
     * {@inheritdoc}
     */
    public function addProvider(ProviderInterface $provider): void
    {
        assert($provider instanceof ItemProviderInterface);
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
