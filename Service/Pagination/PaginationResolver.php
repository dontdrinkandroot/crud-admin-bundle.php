<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class PaginationResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context): PaginationInterface
    {
        if (!$context->isPaginationResolved()) {
            $context->setPagination($this->resolveFromProviders($context));
            $context->setPaginationResolved();
        }

        return $context->getPagination();
    }

    public function resolveFromProviders(CrudAdminContext $context): ?PaginationInterface
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof PaginationProviderInterface);
            if ($provider->supportsPagination($context)) {
                $paginationTarget = $provider->provideCollection($context);
                if (null !== $paginationTarget) {
                    return $paginationTarget;
                }
            }
        }

        return null;
    }
}
