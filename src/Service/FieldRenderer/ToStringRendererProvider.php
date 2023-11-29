<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use BackedEnum;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use UnitEnum;

class ToStringRendererProvider implements FieldRendererProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        if ($value instanceof BackedEnum) {
            return (string)$value->value;
        }

        if ($value instanceof UnitEnum) {
            return $value->name;
        }

        return FieldRenderer::escapeHtml((string)$value);
    }
}
