<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
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
     * @param class-string<T> $entityClass
     */
    public function resolvePagination(string $entityClass): ?PaginationInterface
    {
        foreach ($this->providers as $provider) {
            try {
                return $provider->providePagination($entityClass);
            } catch (UnsupportedByProviderException) {
                /* Continue */
            }
        }

        return null;
    }
}
