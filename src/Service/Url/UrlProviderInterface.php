<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @template T of object
 */
interface UrlProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @return string|null
     */
    public function provideUrl(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?string;
}
