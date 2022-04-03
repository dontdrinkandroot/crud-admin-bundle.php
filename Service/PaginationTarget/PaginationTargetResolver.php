<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class PaginationTargetResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context)
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof PaginationTargetProvider);
            if ($provider->supportsPaginationTarget($context)) {
                $paginationTarget = $provider->providePaginationTarget($context);
                if (null !== $paginationTarget) {
                    return $paginationTarget;
                }
            }
        }

        return null;
    }
}
