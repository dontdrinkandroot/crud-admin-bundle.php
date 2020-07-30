<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Collection;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CollectionResolver extends AbstractProviderService
{
    public function resolve(Request $request): PaginationInterface
    {
        if (!$request->attributes->has(RequestAttributes::DATA)) {
            $request->attributes->set(RequestAttributes::DATA, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttributes::DATA);
    }

    public function resolveFromProviders(Request $request): ?PaginationInterface
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof CollectionProviderInterface);
            if ($provider->supportsRequest($request)) {
                $data = $provider->provideCollection($request);
                if (null !== $data) {
                    return $data;
                }
            }
        }

        return null;
    }
}
