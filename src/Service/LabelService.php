<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

class LabelService
{
    public function __construct(private readonly bool $humanize = false)
    {
    }

    public function getLabel(FieldDefinition|string $value): string
    {
        $resolvedValue = ($value instanceof FieldDefinition) ? $value->propertyPath : $value;

        if ($this->humanize) {
            return $this->humanizePropertyPath($resolvedValue);
        }

        return $resolvedValue;
    }

    public function humanizePropertyPath(string $text): string
    {
        return ucfirst(
            strtolower(trim(preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], str_replace('.', ' ', $text))))
        );
    }
}
