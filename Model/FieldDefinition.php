<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model;

class FieldDefinition
{
    private string $propertyPath;

    private string $label;

    private string $type;

    private bool $sortable;

    public function __construct(string $propertyPath, string $label, string $type, bool $sortable = false)
    {
        $this->propertyPath = $propertyPath;
        $this->label = $label;
        $this->type = $type;
        $this->sortable = $sortable;
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
}
