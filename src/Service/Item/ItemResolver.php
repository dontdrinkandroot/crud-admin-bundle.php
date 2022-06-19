<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class ItemResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context): ?object
    {
        if (!$context->isEntityResolved()) {
            $context->setEntity($this->resolveFromProviders($context));
            $context->setEntityResolved();
        }

        return $context->getEntity();
    }

    private function resolveFromProviders(CrudAdminContext $context): ?object
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof ItemProviderInterface);
            if ($provider->supportsItem($context)) {
                return $provider->provideItem($context);
            }
        }

        return null;
    }
}
