<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model;

class DefaultSort
{
    public function __construct(
        public readonly string $field,
        public readonly string $order = 'asc'
    ) {
    }
}
