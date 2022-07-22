<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class PaginationTargetResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return mixed
     */
    public function resolve(string $crudOperation, string $entityClass): mixed
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof PaginationTargetProvider);
            if ($provider->supportsPaginationTarget($crudOperation, $entityClass)) {
                $paginationTarget = $provider->providePaginationTarget($crudOperation, $entityClass);
                if (null !== $paginationTarget) {
                    return $paginationTarget;
                }
            }
        }

        return null;
    }
}
