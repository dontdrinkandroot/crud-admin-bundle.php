<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends AbstractProviderService<PaginationProviderInterface>
 */
class PaginationResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return PaginationInterface|null
     */
    public function resolve(string $crudOperation, string $entityClass): ?PaginationInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider->supportsPagination($crudOperation, $entityClass)) {
                return $provider->providePagination($crudOperation, $entityClass);
            }
        }

        return null;
    }
}
