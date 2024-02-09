<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface IdProviderInterface extends ProviderInterface
{
    /**
     * @param class-string  $entityClass
     *
     * @return mixed
     */
    public function provideId(string $entityClass, CrudOperation $crudOperation, object $entity): mixed;
}
