<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Doctrine\ORM\QueryBuilder;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class PaginationTargetResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context)
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof PaginationTargetProvider);
            if ($provider->supports($context)) {
                $paginationTarget = $provider->providePaginationTarget($context);
                if (null !== $paginationTarget) {
                    return $paginationTarget;
                }
            }
        }

        return null;
    }
}
