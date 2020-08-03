<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\NewInstance;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultNewInstanceProvider implements NewInstanceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminContext $context): bool
    {
        return true;
    }

    public function provideNewInstance(CrudAdminContext $context): ?object
    {
        $entityClass = $context->getEntityClass();

        return new $entityClass();
    }

}
