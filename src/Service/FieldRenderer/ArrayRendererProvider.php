<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Doctrine\DBAL\Types\Types;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Override;

class ArrayRendererProvider implements FieldRendererProviderInterface
{
    #[Override]
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return 'array' === $fieldDefinition->displayType || Types::SIMPLE_ARRAY === $fieldDefinition->displayType;
    }

    #[Override]
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        return FieldRenderer::escapeHtml(implode(',', Asserted::array($value)));
    }
}
