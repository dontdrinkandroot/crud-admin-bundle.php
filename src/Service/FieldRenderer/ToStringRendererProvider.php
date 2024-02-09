<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use BackedEnum;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Override;
use UnitEnum;

class ToStringRendererProvider implements FieldRendererProviderInterface
{
    #[Override]
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return true;
    }

    #[Override]
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
