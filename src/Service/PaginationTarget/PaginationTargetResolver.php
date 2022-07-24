<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class PaginationTargetResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     *
     * @return mixed
     */
    public function resolve(string $entityClass): mixed
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof PaginationTargetProvider);
            if ($provider->supportsPaginationTarget($entityClass)) {
                $paginationTarget = $provider->providePaginationTarget($entityClass);
                if (null !== $paginationTarget) {
                    return $paginationTarget;
                }
            }
        }

        return null;
    }
}
