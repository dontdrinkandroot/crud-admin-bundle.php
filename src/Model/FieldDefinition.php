<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model;

use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\Form\FormTypeInterface;

class FieldDefinition
{
    /**
     * @param list<CrudOperation> $crudOperations
     * @param class-string<FormTypeInterface>|null $formType
     */
    public function __construct(
        public readonly string $propertyPath,
        public readonly string $displayType,
        public readonly array $crudOperations = [],
        public readonly ?string $formType = null,
        public readonly bool $sortable = false,
        public readonly bool $filterable = false
    ) {
    }
}
