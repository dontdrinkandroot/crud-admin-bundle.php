<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Override;

class NullRendererProvider implements FieldRendererProviderInterface
{
    #[Override]
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return null === $value;
    }

    #[Override]
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        return '';
    }
}
