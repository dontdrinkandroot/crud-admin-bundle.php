<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface TitleProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param CrudOperation $crudOperation
     * @param T|null $entity
     *
     * @return string|null
     *
     */
    public function provideTitle(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?string;
}
