<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Sort;

use Dontdrinkandroot\CrudAdminBundle\Model\DefaultSort;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @template T of object
 */
interface DefaultSortProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     */
    public function provideDefaultSort(string $entityClass): ?DefaultSort;
}
