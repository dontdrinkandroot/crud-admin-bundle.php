<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Override;

/**
 * @extends AbstractProviderService<PaginationTargetProviderInterface>
 */
class PaginationTargetResolver extends AbstractProviderService implements PaginationTargetResolverInterface
{
    #[Override]
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
