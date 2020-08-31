<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class RoutesResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context): ?array
    {
        if (!$context->isRoutesResolved()) {
            $context->setRoutes($this->resolveFromProviders($context));
            $context->setRoutesResolved();
        }

        return $context->getRoutes();
    }

    private function resolveFromProviders(CrudAdminContext $context): ?array
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof RoutesProviderInterface);
            if ($provider->supportsRoutes($context)) {
                $routes = $provider->provideRoutes($context);
                if (null !== $routes) {
                    return $routes;
                }
            }
        }

        return null;
    }
}
