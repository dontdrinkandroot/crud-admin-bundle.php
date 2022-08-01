<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Sort;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\Config\DefaultSortConfig;

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
    public function provideDefaultSort(string $entityClass): DefaultSortConfig
    {
        if ($entityClass !== $this->entityClass) {
            throw new UnsupportedByProviderException($entityClass);
        }

        return (new DefaultSortConfig())
            ->setField($this->field)
            ->setOrder($this->order);
    }
}
