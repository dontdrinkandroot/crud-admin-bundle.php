<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

/**
 * Provides labels for field definitions and property paths.
 */
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

    /**
     * Converts a property path to a human readable label, e.g. "firstName" to "First name".
     */
    public function humanizePropertyPath(string $text): string
    {
        return ucfirst(
            strtolower(
                trim((string)preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], str_replace('.', ' ', $text)))
            )
        );
    }
}
