<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Doctrine\DBAL\Types\Types;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Override;

class JsonRendererProvider implements FieldRendererProviderInterface
{
    #[Override]
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return Types::JSON === $fieldDefinition->displayType;
    }

    #[Override]
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        return FieldRenderer::escapeHtml(json_encode($value, JSON_THROW_ON_ERROR));
    }
}
