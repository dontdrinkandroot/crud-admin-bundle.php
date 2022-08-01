<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Sort;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\DefaultSort;

class StaticDefaultSortProvider implements DefaultSortProviderInterface
{
    /**
     * @param class-string $entityClass
     * @param string       $field
     * @param string       $order
     */
    public function __construct(
        private readonly string $entityClass,
        private readonly string $field,
        private readonly string $order = 'asc'
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function provideDefaultSort(string $entityClass): DefaultSort
    {
        if ($entityClass !== $this->entityClass) {
            throw new UnsupportedByProviderException($entityClass);
        }

        return new DefaultSort($this->field, $this->order);
    }
}
