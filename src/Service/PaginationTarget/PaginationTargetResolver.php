<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<PaginationTargetProvider>
 */
class PaginationTargetResolver extends AbstractProviderService
{
    /**
     * @param class-string $entityClass
     *
     * @return mixed
     */
    public function resolve(string $entityClass): mixed
    {
        foreach ($this->providers as $provider) {
            try {
                return $provider->providePaginationTarget($entityClass);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
