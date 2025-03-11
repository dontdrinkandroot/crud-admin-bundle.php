<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Sort;

use Dontdrinkandroot\CrudAdminBundle\Model\DefaultSort;
use Override;

/**
 * @template T of object
 * @implements DefaultSortProviderInterface<T>
 */
class StaticDefaultSortProvider implements DefaultSortProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     */
    public function __construct(
        private readonly string $entityClass,
        private readonly string $field,
        private readonly string $order = 'asc'
    ) {
    }

    #[Override]
    public function provideDefaultSort(string $entityClass): ?DefaultSort
    {
        if ($entityClass !== $this->entityClass) {
            return null;
        }

        return new DefaultSort($this->field, $this->order);
    }
}
