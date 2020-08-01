<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\NewInstance;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class NewInstanceResolver extends AbstractProviderService
{
    public function resolve(Request $request): ?object
    {
        if (!$request->attributes->has(RequestAttributes::DATA)) {
            $request->attributes->set(RequestAttributes::DATA, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttributes::DATA);
    }

    private function resolveFromProviders(Request $request): ?object
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof NewInstanceProviderInterface);
            if ($provider->supports(
                RequestAttributes::getEntityClass($request),
                RequestAttributes::getOperation($request),
                $request
            )) {
                $entity = $provider->provideNewInstance($request);
                if (null !== $entity) {
                    return $entity;
                }
            }
        }

        return null;
    }
}
