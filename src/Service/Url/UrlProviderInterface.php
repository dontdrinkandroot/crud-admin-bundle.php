<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\CrudAdminBundle\Exception\EndProviderChainException;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface UrlProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return bool
     */
    public function supportsUrl(string $crudOperation, string $entityClass, ?object $entity): bool;

    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return string
     */
    public function provideUrl(string $crudOperation, string $entityClass, ?object $entity): string;
}
