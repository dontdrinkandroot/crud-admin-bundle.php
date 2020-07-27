<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\RequestProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class IdResolver implements ProviderServiceInterface
{
    /** @var IdProviderInterface[] */
    private $providers = [];

    public function addProvider(ProviderInterface $provider): void
    {
        assert($provider instanceof IdProviderInterface);
        $this->providers[] = $provider;
    }

    public function resolve(object $entity)
    {
        foreach ($this->providers as $provider) {
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
