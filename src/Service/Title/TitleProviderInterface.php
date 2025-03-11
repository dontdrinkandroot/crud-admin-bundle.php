<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @template T of object
 */
interface TitleProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @param T|null $entity
     * @return string|null
     */
    public function provideTitle(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?string;
}
