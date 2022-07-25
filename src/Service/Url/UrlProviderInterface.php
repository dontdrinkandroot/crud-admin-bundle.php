<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface UrlProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return bool
     */
    public function supportsUrl(CrudOperation $crudOperation, string $entityClass, ?object $entity): bool;

    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return string
     */
    public function provideUrl(CrudOperation $crudOperation, string $entityClass, ?object $entity): string;
}
