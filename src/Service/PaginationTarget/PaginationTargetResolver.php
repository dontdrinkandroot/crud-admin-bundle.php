<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<PaginationTargetProvider>
 */
class PaginationTargetResolver extends AbstractProviderService
{
    /**
     * @param class-string $entityClass
     */
    public function resolvePaginationTarget(string $entityClass): mixed
    {
        foreach ($this->providers as $provider) {
            $paginationTarget = $provider->providePaginationTarget($entityClass);
            if (null !== $paginationTarget) {
                return $paginationTarget;
            }
        }

        return null;
    }
}
