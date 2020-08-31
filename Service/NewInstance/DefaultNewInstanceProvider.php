<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\NewInstance;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultNewInstanceProvider implements NewInstanceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsNewInstance(CrudAdminContext $context): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideNewInstance(CrudAdminContext $context): ?object
    {
        $entityClass = $context->getEntityClass();

        return new $entityClass();
    }
}
