<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Sort;

use Dontdrinkandroot\CrudAdminBundle\Model\DefaultSort;

/**
 * @template T of object
 */
interface DefaultSortProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     */
    public function provideDefaultSort(string $entityClass): ?DefaultSort;
}
