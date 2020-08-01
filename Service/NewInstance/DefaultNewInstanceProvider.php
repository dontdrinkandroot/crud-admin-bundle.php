<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\NewInstance;

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
    public function supports(string $entityClass, string $crudOperation, Request $request): bool
    {
        return true;
    }

    public function provideNewInstance(Request $request): ?object
    {
        $entityClass = RequestAttributes::getEntityClass($request);

        return new $entityClass();
    }

}
