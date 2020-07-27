<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttribute;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class RoutesResolver implements ProviderServiceInterface
{
    /** @var RoutesProviderInterface[] */
    private $providers = [];

    /**
     * {@inheritdoc}
     */
    public function addProvider(ProviderInterface $provider): void
    {
        assert($provider instanceof RoutesProviderInterface);
        $this->providers[] = $provider;
    }

    public function resolve(Request $request): ?array
    {
        if (!$request->attributes->has(RequestAttribute::ROUTES)) {
            $request->attributes->set(RequestAttribute::ROUTES, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttribute::ROUTES);
    }

    private function resolveFromProviders(Request $request): ?array
    {
        foreach ($this->providers as $routeProvider) {
            if ($routeProvider->supports($request)) {
                $routes = $routeProvider->provideRoutes($request);
                if (null !== $routes) {
                    return $routes;
                }
            }
        }

        return null;
    }
}
