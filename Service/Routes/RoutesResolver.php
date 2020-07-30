<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class RoutesResolver extends AbstractProviderService
{
    public function resolve(Request $request): ?array
    {
        if (!$request->attributes->has(RequestAttributes::ROUTES)) {
            $request->attributes->set(RequestAttributes::ROUTES, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttributes::ROUTES);
    }

    private function resolveFromProviders(Request $request): ?array
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof RoutesProviderInterface);
            if ($provider->supportsRequest($request)) {
                $routes = $provider->provideRoutes($request);
                if (null !== $routes) {
                    return $routes;
                }
            }
        }

        return null;
    }
}
