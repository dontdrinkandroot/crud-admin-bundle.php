<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ItemPersister implements ProviderServiceInterface
{
    /** @var ItemPersisterProviderInterface[] */
    private $providers = [];

    /**
     * {@inheritdoc}
     */
    public function addProvider(ProviderInterface $provider): void
    {
        assert($provider instanceof ItemPersisterProviderInterface);
        $this->providers[] = $provider;
    }

    public function persist(Request $request): bool
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($request)) {
                $result = $provider->persist($request);
                if (true === $result) {
                    RequestAttributes::setPersistSuccess($request, $result);

                    return true;
                }
            }
        }

        return false;
    }
}
