<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface IdProviderInterface extends ProviderInterface
{
    /**
     * @param CrudOperation $crudOperation
     * @param class-string  $entityClass
     * @param object        $entity
     *
     * @return mixed
     * @throws UnsupportedByProviderException
     */
    public function provideId(CrudOperation $crudOperation, string $entityClass, object $entity): mixed;
}
