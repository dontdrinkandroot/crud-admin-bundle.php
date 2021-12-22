<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use RuntimeException;

/**
 * @extends AbstractProviderService<PaginationProviderInterface>
 */
class PaginationResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context): PaginationInterface
    {
        if (!$context->isPaginationResolved()) {
            $context->setPagination($this->resolveFromProviders($context));
            $context->setPaginationResolved();
        }

        if (null === ($pagination = $context->getPagination())) {
            throw new RuntimeException('Could not resolve pagination');
        }

        return $pagination;
    }

    public function resolveFromProviders(CrudAdminContext $context): ?PaginationInterface
    {
        foreach ($this->getProviders() as $provider) {
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
