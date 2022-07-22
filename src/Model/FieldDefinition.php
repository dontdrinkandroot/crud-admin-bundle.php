<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model;

class FieldDefinition
{
    public function __construct(
        public readonly string $propertyPath,
        public readonly string $type,
        public readonly bool $sortable = false,
        public readonly bool $filterable = false
    ) {
    }
}
