<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface IdProviderInterface extends ProviderInterface
{
    /**
     * @param class-string  $entityClass
     * @param CrudOperation $crudOperation
     * @param object        $entity
     *
     * @return mixed
     * @throws UnsupportedByProviderException
     */
    public function provideId(string $entityClass, CrudOperation $crudOperation, object $entity): mixed;
}
