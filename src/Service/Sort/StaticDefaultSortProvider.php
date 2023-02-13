<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Sort;

use Dontdrinkandroot\CrudAdminBundle\Model\DefaultSort;

class StaticDefaultSortProvider implements DefaultSortProviderInterface
{
    /**
     * @param class-string $entityClass
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
    public function provideDefaultSort(string $entityClass): ?DefaultSort
    {
        if ($entityClass !== $this->entityClass) {
            return null;
        }

        return new DefaultSort($this->field, $this->order);
    }
}
