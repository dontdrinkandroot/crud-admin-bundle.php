<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\NewInstance;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Symfony\Component\HttpFoundation\Request;

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
            if ($provider->supports($context)) {
                $entity = $provider->provideNewInstance($context);
                if (null !== $entity) {
                    return $entity;
                }
            }
        }

        return null;
    }
}
