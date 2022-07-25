<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface IdProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T               $entity
     *
     * @return bool
     */
    public function supportsId(CrudOperation $crudOperation, string $entityClass, object $entity): bool;

    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T               $entity
     *
     * @return mixed
     */
    public function provideId(CrudOperation $crudOperation, string $entityClass, object $entity): mixed;
}
