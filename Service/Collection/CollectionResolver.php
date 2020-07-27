<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Collection;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CollectionResolver implements ProviderServiceInterface
{
    /** @var CollectionProviderInterface[] */
    private array $providers = [];

    /**
     * {@inheritdoc}
     */
    public function addProvider(ProviderInterface $provider): void
    {
        assert($provider instanceof CollectionProviderInterface);
        $this->providers[] = $provider;
    }

    public function resolve(Request $request): PaginationInterface
    {
        if (!$request->attributes->has(RequestAttributes::DATA)) {
            $request->attributes->set(RequestAttributes::DATA, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttributes::DATA);
    }

    public function resolveFromProviders(Request $request): ?PaginationInterface
    {
        foreach ($this->providers as $collectionProvider) {
            if ($collectionProvider->supports($request)) {
                $data = $collectionProvider->provideCollection($request);
                if (null !== $data) {
                    return $data;
                }
            }
        }

        return null;
    }
}
