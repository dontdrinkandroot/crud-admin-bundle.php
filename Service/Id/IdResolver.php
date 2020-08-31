<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class IdResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context)
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof IdProviderInterface);
            if ($provider->supportsId($context)) {
                $id = $provider->provideId($context);
                if (null !== $id) {
                    return $id;
                }
            }
        }

        return null;
    }
}
