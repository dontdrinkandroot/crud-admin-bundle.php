<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @template T of object
 */
interface IdProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @return mixed
     */
    public function provideId(string $entityClass, CrudOperation $crudOperation, object $entity): mixed;
}
