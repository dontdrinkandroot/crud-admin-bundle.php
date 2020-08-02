<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Doctrine\ORM\QueryBuilder;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class PaginationTargetResolver extends AbstractProviderService
{
    public function resolve(string $entityClass, string $crudOperation, Request $request)
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof PaginationTargetProvider);
            if ($provider->supports($entityClass, $crudOperation, $request)) {
                $paginationTarget = $provider->providePaginationTarget($entityClass, $crudOperation, $request);
                if (null !== $paginationTarget) {
                    return $paginationTarget;
                }
            }
        }

        return null;
    }
}
