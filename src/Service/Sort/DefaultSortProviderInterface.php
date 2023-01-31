<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Sort;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\DefaultSort;

interface DefaultSortProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     * @throws UnsupportedByProviderException
     */
    public function provideDefaultSort(string $entityClass): ?DefaultSort;
}
