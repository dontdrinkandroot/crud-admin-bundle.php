<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class RoutesResolver extends AbstractProviderService
{
    /** @return array<string,string>|null */
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
                if (null !== $routes = $provider->provideRoutes($context)) {
                    return $routes;
                }
            }
        }

        return null;
    }
}
