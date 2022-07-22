<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface IdProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     * @param T               $entity
     *
     * @return bool
     */
    public function supportsId(string $crudOperation, string $entityClass, object $entity): bool;

    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     * @param T               $entity
     *
     * @return mixed
     */
    public function provideId(string $crudOperation, string $entityClass, object $entity): mixed;
}
