<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model;

/**
 * Describes the default sort field and order of a list.
 */
class DefaultSort
{
    public function __construct(
        public readonly string $field,
        public readonly string $order = 'asc'
    ) {
    }
}
