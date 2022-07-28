<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Sort;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\Config\DefaultSortConfig;

interface DefaultSortProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     * @return DefaultSortConfig|null
     */
    public function provideDefaultSort(string $entityClass): ?DefaultSortConfig;
}
