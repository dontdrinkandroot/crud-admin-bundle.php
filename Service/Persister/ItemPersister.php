<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ItemPersister extends AbstractProviderService
{
    public function persistItem(CrudAdminContext $context): bool
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof ItemPersisterProviderInterface);
            if ($provider->supportsPersist($context)) {
                $result = $provider->persist($context);
                if (true === $result) {
                    $context->setItemPersisted();
                    return true;
                }
            }
        }

        return false;
    }
}
