<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
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
            if ($provider->supports($context)) {
                $entity = $provider->provideItem($context);
                if (null !== $entity) {
                    return $entity;
                }
            }
        }

        return null;
    }
}
