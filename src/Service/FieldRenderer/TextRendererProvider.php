<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use BackedEnum;
use Doctrine\DBAL\Types\Types;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Override;
use UnitEnum;

class TextRendererProvider implements FieldRendererProviderInterface
{

    #[Override]
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return $fieldDefinition->displayType === Types::TEXT;
    }

    #[Override]
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        $lines = explode("\n", $value);
        $escapedLines = array_map(fn($line) => FieldRenderer::escapeHtml($line), $lines);

        return implode("<br/>", $escapedLines);
    }
}
