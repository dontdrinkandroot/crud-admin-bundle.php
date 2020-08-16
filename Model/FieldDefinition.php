<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class FieldDefinition
{
    private string $propertyPath;

    private string $type;

    private bool $sortable;

    private bool $filterable;

    public function __construct(
        string $propertyPath,
        string $type,
        bool $sortable = false,
        bool $filterable = false
    ) {
        $this->propertyPath = $propertyPath;
        $this->type = $type;
        $this->sortable = $sortable;
        $this->filterable = $filterable;
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function isFilterable(): bool
    {
        return $this->filterable;
    }
}
