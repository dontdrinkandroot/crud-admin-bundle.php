<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\NewInstance;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class NewInstanceResolver extends AbstractProviderService
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
            assert($provider instanceof NewInstanceProviderInterface);
            if ($provider->supportsNewInstance($context)) {
                $entity = $provider->provideNewInstance($context);
                if (null !== $entity) {
                    return $entity;
                }
            }
        }

        return null;
    }
}
