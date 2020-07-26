<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model;

class FieldDefinition
{
    private string $propertyPath;

    private string $label;

    private string $type;

    private bool $sortable;

    private bool $filterable;

    public function __construct(
        string $propertyPath,
        string $label,
        string $type,
        bool $sortable = false,
        bool $filterable = false
    ) {
        $this->propertyPath = $propertyPath;
        $this->label = $label;
        $this->type = $type;
        $this->sortable = $sortable;
        $this->filterable = $filterable;
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    public function getLabel(): string
    {
        return $this->label;
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
