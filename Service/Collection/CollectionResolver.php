<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Collection;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Dontdrinkandroot\CrudAdminBundle\Service\Collection\CollectionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CollectionResolver implements ProviderServiceInterface
{
    /** @var CollectionProviderInterface[] */
    private array $providers = [];

    /**
     * {@inheritdoc}
     */
    public function addProvider(ProviderInterface $provider): void
    {
        assert($provider instanceof CollectionProviderInterface);
        $this->providers[] = $provider;
    }

    public function resolve(Request $request): PaginationInterface
    {
        $crudAdminRequest = new CrudAdminRequest($request);
        $data = $crudAdminRequest->getData();
        if (null !== $data) {
            return $data;
        }

        foreach ($this->providers as $collectionProvider) {
            if ($collectionProvider->supports($crudAdminRequest->getRequest())) {
                $data = $collectionProvider->provideCollection($crudAdminRequest->getRequest());
                if (null !== $data) {
                    $crudAdminRequest->setData($data);

                    return $data;
                }
            }
        }

        throw new RuntimeException('Could not list entities');
    }
}
