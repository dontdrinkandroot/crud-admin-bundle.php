<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Sort;

use Dontdrinkandroot\CrudAdminBundle\Model\DefaultSort;

interface DefaultSortProviderInterface
{
    /**
     * @param class-string $entityClass
     */
    public function provideDefaultSort(string $entityClass): ?DefaultSort;
}
