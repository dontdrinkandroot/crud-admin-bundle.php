<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class IdResolver extends AbstractProviderService
{
    public function resolve(object $entity)
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof IdProviderInterface);
            if ($provider->supportsEntity($entity)) {
                $result = $provider->provideId($entity);
                if (null !== $result) {
                    return $result;
                }
            }
        }

        return null;
    }
}
