<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Override;

/**
 * @template P of PaginationProviderInterface
 * @extends AbstractProviderService<P>
 */
class PaginationResolver extends AbstractProviderService implements PaginationResolverInterface
{
    /**
     * @template T of object
     * @return PaginationInterface<mixed,T>|null
     */
    #[Override]
    public function resolvePagination(string $entityClass): ?PaginationInterface
    {
        foreach ($this->providers as $provider) {
            /** @var PaginationInterface<mixed,T>|null $pagination */
            $pagination = $provider->providePagination($entityClass);
            if (null !== $pagination) {
                return $pagination;
            }
        }

        return null;
    }
}
